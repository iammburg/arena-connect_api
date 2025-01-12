<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Bank;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
                'bank:id,bank_name,account_number,field_centre_id',
            ])->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfully get data on Payments',
                'data' => $payments,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get data on Payments',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPaymentByUser($user_id)
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
            ])->where('user_id', $user_id)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved payment data for the user.',
                'data' => $payments,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment data.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function showPaymentByUser($user_id, $payment_id)
    {
        try {
            $payment = Payments::with([
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
            ])->where('user_id', $user_id)
                ->where('id', $payment_id)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved payment details for the user.',
                'data' => $payment,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment details.',
                'error' => $th->getMessage(),
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

    public function getTotalRevenue()
    {
        // Validasi dan keamanan tambahan
        try {
            // Menggunakan model Payments untuk perhitungan total
            $totalRevenue = Payments::where('status', 'selesai')->sum('total_payment');
            $totalTransaksi = Payments::where('status', 'selesai')->count();


            // Mengembalikan response JSON dengan status sukses
            return response()->json([
                'status' => 'success',
                'total_revenue' => $totalRevenue,
                'total_transaksi' => $totalTransaksi,
            ], 200);
        } catch (\Exception $e) {
            // Menangani kesalahan jika terjadi
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch total revenue',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            // Validasi data
            $validator = Validator::make($request->all(), [
                'status' => 'required|string|max:255', // Add specific allowed statuses
            ]);

            // Jika validasi gagal, kembalikan error
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Temukan payment berdasarkan ID
            $payment = Payments::findOrFail($id);

            // Update status
            $payment->status = $request->input('status');
            $payment->save();

            // Muat relasi yang mungkin diperlukan
            $payment->load('user', 'field', 'booking', 'bank');

            // Berikan respons
            return response()->json([
                'message' => 'Payment updated successfully',
                'data' => $payment,
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Tangani jika payment tidak ditemukan
            return response()->json([
                'message' => 'Payment not found',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            // Tangani error umum
            return response()->json([
                'message' => 'Error updating payment',
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
