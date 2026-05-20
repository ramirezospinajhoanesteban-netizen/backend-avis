<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;

class DonationController extends Controller
{
    public function init(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000|max:20000000',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'nullable|string|max:500',
        ]);

        $reference = 'AVIS-DON-' . strtoupper(uniqid());

        $donation = Donation::create([
            'reference' => $reference,
            'amount' => $validated['amount'] * 100, // Wompi expects cents
            'currency' => 'COP',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
            'status' => 'PENDING',
        ]);

        // Wompi Integrity hash
        $secret = env('WOMPI_INTEGRITY_SECRET'); 
        $signature = null;
        if ($secret) {
            $stringToHash = $donation->reference . $donation->amount . $donation->currency . $secret;
            $signature = hash('sha256', $stringToHash);
        }

        return response()->json([
            'reference' => $donation->reference,
            'amount_in_cents' => $donation->amount,
            'currency' => $donation->currency,
            'public_key' => env('WOMPI_PUBLIC_KEY'),
            'signature' => $signature,
        ]);
    }

    public function webhook(Request $request)
    {
        // Wompi sends an event payload
        $event = $request->input('event');
        $data = $request->input('data.transaction');

        if (!$event || !$data) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // Validate event signature if needed (using WOMPI_EVENTS_SECRET)
        // Here we just look up the donation and update it.
        $reference = $data['reference'] ?? null;
        if ($reference) {
            $donation = Donation::where('reference', $reference)->first();
            if ($donation) {
                $status = $data['status']; // APPROVED, DECLINED, ERROR, PENDING
                $donation->status = $status;
                $donation->wompi_transaction_id = $data['id'] ?? null;
                $donation->save();
            }
        }

        return response()->json(['success' => true]);
    }
}
