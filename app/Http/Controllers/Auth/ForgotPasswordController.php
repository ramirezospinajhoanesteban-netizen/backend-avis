<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\RecoveryCodeMail;

class ForgotPasswordController extends Controller
{
    public function sendResetLink(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        
        // Generate a 6-digit random code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Overwrite any existing reset tokens for this email
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $code, 'created_at' => now()]
        );
        
        // Send email
        Mail::to($request->email)->send(new RecoveryCodeMail($code));

        return response()->json(['message' => 'Enlace de recuperación enviado a tu correo'], 200);
    }
    
    // New endpoint to verify code without modifying password yet
    public function verifyCode(Request $request){
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|digits:6',
        ]);
        
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        
        if (!$record || $record->token !== $request->code) {
            return response()->json(['message' => 'Código inválido'], 400);
        }
        
        // Check expiration (e.g., 15 minutes)
        $createdAt = \Carbon\Carbon::parse($record->created_at);
        if (now()->diffInMinutes($createdAt) > 15) {
            return response()->json(['message' => 'El código ha expirado'], 400);
        }
        
        return response()->json(['message' => 'Código válido'], 200);
    }

    public function resetPassword(Request $request){
        $request->validate([
            'code' => 'required|digits:6',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        
        if (!$record || $record->token !== $request->code) {
            return response()->json(['message' => 'Código inválido'], 400);
        }
        
        $createdAt = \Carbon\Carbon::parse($record->created_at);
        if (now()->diffInMinutes($createdAt) > 15) {
            return response()->json(['message' => 'El código ha expirado'], 400);
        }
        
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = bcrypt($request->password);
            $user->save();
            
            // Delete the token
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            
            return response()->json(['message' => 'Contraseña actualizada correctamente'], 200);
        }
        
        return response()->json(['message' => 'Usuario no encontrado'], 400);
    }
}