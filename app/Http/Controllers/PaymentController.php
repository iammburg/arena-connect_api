<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payments::with([
            'field' => function ($query) {
                $query->select('fields.id as field_id', 'fields.name', 'fields.field_centre_id')
                    ->with([
                        'fieldCentre:id,name,rating,address',
                    ]);
            },

            'booking' => function ($query) {
                $query->select('id', 'field_id', 'booking_start', 'booking_end', 'date');
            },
            'user:id,name,email',
            'bank:id,bank_name, account_number, field_centre_id, user_id',
        ])->get();
        return view('payments.index', compact('payments'));
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
