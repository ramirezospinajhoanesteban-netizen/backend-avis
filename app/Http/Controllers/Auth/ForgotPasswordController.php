<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
 public function sendResetlink(Request $request){
         $request->validate([
            'email'=>'required|email|esxists:users,email',
         ]);
         $status = Password::sendResetLink(
            $request->only('email')
         );
      return $status === Password::RESET_LINK_SENT
         ? response()->json(['message' => 'Enlace de recuperación enviado a tu correo'], 200)
         : response()->json(['message' => 'No se pudo enviar el enlace, verifica el correo'], 400);
  }

 public function resetPassword(Request $request){
  $request->validate([
   'token' => 'required',
   'email' => 'required|email',
   'password' => 'required|min:8|confirmed',
   'password_confirmation' => 'required',
  ]);

   $status = Password::reset(
      $request->only('email','password','password_confirmation','token'),
      function ($user, $password){
         $user->password= bcrypt($password);
         $user->save();
      }
   );

   return $status == Password::PASSWORD_RESET
   ? response()->json(['message' => 'contraseña actualizada correctamente'],200): response()->json(['message' =>'token invalido o expirado'],400);
 }






}