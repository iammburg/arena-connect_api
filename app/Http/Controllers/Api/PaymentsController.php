<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $payments = Payments::all();

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
        $add_payments = new Payments();
        $rules = [
            'user_id' => 'required',
            'booking_id' => 'required',
            'total_payment' => 'required',
            'payment_method' => 'required',
            'status' => 'required',
            'order_id' => 'required|numeric|min:0',
            'payment_code' => 'required|numeric|min:0',
            'receipt.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date' => 'required|date',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors(),
            ], 422);
        }

        $imagePaths = [];

        if ($request->hasFile('receipt')) {
            foreach ($request->file('receipt') as $image) {
                $path = $image->store('images', 'public');
                $imagePaths[] = url('storage/' . $path);
            }
        }

        $add_payments->user_id = $request->user_id;
        $add_payments->booking_id = $request->booking_id;
        $add_payments->total_payment = $request->total_payment;
        $add_payments->payment_method = $request->payment_method;
        $add_payments->status = $request->status;
        $add_payments->order_id = $request->order_id;
        $add_payments->payment_code = $request->payment_code;
        $add_payments->receipt = json_encode($imagePaths, JSON_UNESCAPED_SLASHES);
        $add_payments->date = $request->date;

        $add_payments->save();

        return response()->json([
            'success' => true,
            'message' => 'Add new Payments successfully',
            'data' => $add_payments,
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
                            'fieldCentre:id,name,rating,address'
                        ]);
                },

                'booking' => function ($query) {
                    $query->select('id', 'field_id', 'booking_start', 'booking_end', 'date');
                }
            ])
                ->find($id);
            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved payment details',
                'data' => $payments

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
    public function edit(Payments $payments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Aturan validasi
            $rules = [
                'user_id' => 'required',
                'booking_id' => 'required',
                'total_payment' => 'required',
                'payment_method' => 'required',
                'status' => 'required',
                'order_id' => 'required|numeric|min:0',
                'payment_code' => 'required|numeric|min:0',
                'receipt.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'date' => 'required|date',
            ];

            // Validasi input
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                \Log::error('Validation Errors:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'data' => $validator->errors(),
                ], 422);
            }
            // Temukan data berdasarkan ID
            $payment = Payments::findOrFail($id);

            // // Mengelola file receipt jika ada
            $imagePaths = [];

            if ($request->hasFile('receipt')) {
                foreach ($request->file('receipt') as $image) {
                    $path = $image->store('images', 'public');
                    $imagePaths[] = url('storage/' . $path);
                }
            }

            // Update data Payment secara langsung
            $payment->update([
                'user_id' => $request->user_id,
                'booking_id' => $request->booking_id,
                'total_payment' => $request->total_payment,
                'payment_method' => $request->payment_method,
                'status' => $request->status,
                'order_id' => $request->order_id,
                'payment_code' => $request->payment_code,
                'receipt' => !empty($imagePaths) ? json_encode($imagePaths, JSON_UNESCAPED_SLASHES) : $payment->receipt, // Hanya jika ada gambar yang diunggah
                'date' => $request->date,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully',
                'data' => $payment,
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Payment Update Failed:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments $payments)
    {
        //
    }
}
