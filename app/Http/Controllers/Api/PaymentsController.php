<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            'receipt' => 'required|numeric|min:0',
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

        $add_payments->user_id = $request->user_id;
        $add_payments->booking_id = $request->booking_id;
        $add_payments->total_payment = $request->total_payment;
        $add_payments->payment_method = $request->payment_method;
        $add_payments->status = $request->status;
        $add_payments->order_id = $request->order_id;
        $add_payments->receipt = $request->receipt;
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
    public function update(Request $request,  $id)
    {
        try{
            $payments = Payments::findOrFail($id);
            $rules = [
                'user_id' => 'required',
                'booking_id' => 'required',
                'total_payment' => 'required',
                'payment_method' => 'required',
                'status' => 'required',
                'order_id' => 'required|numeric|min:0',
                'receipt' => 'required|numeric|min:0',
                'date' => 'required|date',

            ];
            $validator = validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json([
                    'success' =>false,
                    'message'=>'Validation errors',
                    'data' => $validator->errors(),
                ],422);
            }
            $payments->user_id = $request->user_id;
            $payments->booking_id = $request->booking_id;
            $payments->total_payment = $request->total_payment;
            $payments->payment_method = $request->payment_method;
            $payments->status = $request->status;
            $payments->order_id = $request->order_id;
            $payments->receipt = $request->receipt;
            $payments->date = $request->date;

            $payments->save();
            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully',
                'data' => $payments,
            ], 200);
                
        }catch(\Exception $e){
            return response()->json([
                'success' =>false,
                'message' => 'Failed to update payment',
                'error' => $e->getMessage(),
            ],500);
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
