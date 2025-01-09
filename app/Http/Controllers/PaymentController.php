<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'Admin Aplikasi') {
            $payments = Payments::with('user', 'booking', 'field', 'bank')->get();
        } else if (Auth::user()->role == 'Admin Lapangan') {
            $payments = Payments::with('user', 'booking', 'field', 'bank')->whereHas('booking.field.fieldCentre', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })->get();
        } else if (Auth::user()->role == 'Customer') {
            $payments = Payments::with('user', 'booking', 'field', 'bank')->where('user_id', Auth::user()->id)->get();
        }
        return view('payments.index', compact('payments'));
    }

    public function approvePayment(Payments $payment)
    {
        $payment->update([
            'status' => 'Selesai',
        ]);

        $field = Field::find($payment->booking->field_id);
        $field->update([
            'status' => 'Telah dibooking',
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Pembayaran berhasil dikonfirmasi');
    }

    public function rejectPayment(Payments $payment)
    {
        $payment->update([
            'status' => 'Ditolak',
        ]);

        $field = Field::find($payment->booking->field_id);
        $field->update([
            'status' => 'Tersedia',
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Pembayaran telah ditolak');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Payments $payments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payments $payments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payments $payments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments $payments)
    {
        //
    }
}
