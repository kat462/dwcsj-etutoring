<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'nullable|numeric',
            'method' => 'nullable|string',
            'reference' => 'nullable|string',
            'is_optional' => 'boolean',
        ]);

        $payment = Payment::create([
            'user_id' => Auth::id(),
            'booking_id' => $validated['booking_id'],
            'amount' => $validated['amount'] ?? null,
            'status' => 'paid', // Mark as paid immediately for demo; replace with real gateway logic
            'method' => $validated['method'] ?? null,
            'reference' => $validated['reference'] ?? null,
            'is_optional' => $validated['is_optional'] ?? true,
        ]);

        // Update booking payment status
        $booking = \App\Models\Booking::find($validated['booking_id']);
        if ($booking) {
            $booking->payment_status = 'paid';
            $booking->save();
        }

        return redirect()->back()->with('success', 'Payment successful.');
    }

    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return view('payments.show', compact('payment'));
    }
}
