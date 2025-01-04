<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Bank;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
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
            ])->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfully get data on Payments Status',
                'data' => $payments,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get data on Payments Status',
                'error' => $e->getMessage(),
            ], 500);
        }
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
        //Validate Form
        $request->validate([
            'user_id' => 'required',
            'booking_id' => 'required',
            'total_payment' => 'required',
            'status' => 'required',
            'order_id' => 'required|numeric|min:0',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        //Check if Image is Uploaded
        if ($request->hasFile('receipt')) {
            //Upload Image
            $receipt = $request->file('receipt');
            $receipt->storeAs('public/receipts', $receipt->hashName());

            //Create Payments with Image
            $payment = Payments::create([
                'user_id' => $request->user_id,
                'booking_id' => $request->booking_id,
                'total_payment' => $request->total_payment,
                'status' => $request->status,
                'order_id' => $request->order_id,
                'receipt' => $receipt->hashName(),
            ]);
        } else {
            //Create Payments without Image
            $payment = Payments::create([
                'user_id' => $request->user_id,
                'booking_id' => $request->booking_id,
                'total_payment' => $request->total_payment,
                'status' => $request->status,
                'order_id' => $request->order_id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Add new Payments successfully',
            'data' => $payment,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
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
                'bank:id,bank_name,account_number,field_centre_id',
            ])
                ->find($id);
            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved payment details',
                'data' => $payments,

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment details',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //Get Product by ID
        $payment = Payments::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            //Validate Form
            $request->validate([
                'user_id' => 'nullable',
                'booking_id' => 'nullable',
                'total_payment' => 'required',
                'payment_id' => 'required|exists:banks,id',
                'status' => 'nullable',
                'order_id' => 'nullable|numeric|min:0',
                'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            //Get Product by ID
            $payment = Payments::findOrFail($id);

            //Check if Image is Uploaded
            if ($request->hasFile('receipt')) {
                //Upload New Image
                $receipt = $request->file('receipt');
                $receipt->storeAs('public/receipts', $receipt->hashName());

                //Delete Old Image
                Storage::delete("public/receipts/{$payment->receipt}");

                //Update Product with new Image
                $payment->update([
                    'user_id' => $request->user_id,
                    'booking_id' => $request->booking_id,
                    'total_payment' => $request->total_payment,
                    'payment_id' => $request->payment_id,
                    'status' => $request->status,
                    'order_id' => $request->order_id,
                    'receipt' => $receipt->hashName(),
                ]);
            } else {
                //Update Payment without Image
                $payment->update([
                    'user_id' => $request->user_id,
                    'booking_id' => $request->booking_id,
                    'total_payment' => $request->total_payment,
                    'payment_id' => $request->payment_id,
                    'status' => $request->status,
                    'order_id' => $request->order_id,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully',
                'data' => $payment,
            ], 200);


        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePayment(Request $request, $id)
    {
        try {
            //Validate Form
            $request->validate([
                'user_id' => 'nullable',
                'booking_id' => 'nullable',
                'total_payment' => 'required',
                'payment_id' => 'required|exists:banks,id',
                'status' => 'nullable',
                'order_id' => 'nullable|numeric|min:0',
                'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            //Get Product by ID
            $payment = Payments::findOrFail($id);

            //Check if Image is Uploaded
            if ($request->hasFile('receipt')) {
                //Upload New Image
                $receipt = $request->file('receipt');
                $receipt->storeAs('public/receipts', $receipt->hashName());

                //Delete Old Image
                Storage::delete("public/receipts/{$payment->receipt}");

                //Update Product with new Image
                $payment->update([
                    'user_id' => $request->user_id,
                    'booking_id' => $request->booking_id,
                    'total_payment' => $request->total_payment,
                    'payment_id' => $request->payment_id,
                    'status' => $request->status,
                    'order_id' => $request->order_id,
                    'receipt' => $receipt->hashName(),
                ]);
            } else {
                //Update Payment without Image
                $payment->update([
                    'user_id' => $request->user_id,
                    'booking_id' => $request->booking_id,
                    'total_payment' => $request->total_payment,
                    'payment_id' => $request->payment_id,
                    'status' => $request->status,
                    'order_id' => $request->order_id,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully',
                'data' => $payment,
            ], 200);


        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getBanksByFieldCentreId($field_centre_id)
    {
        $banks = Bank::where('field_centre_id', $field_centre_id)->get();

        if ($banks->isEmpty()) {
            return response()->json([
                'message' => 'No banks found for the given field_centre_id',
            ], 404);
        }

        return response()->json([
            'data' => $banks,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments $payments)
    {
        //
    }
}
