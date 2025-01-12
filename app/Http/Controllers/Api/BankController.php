<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FieldCentre;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Bank;

class BankController extends Controller
{
    public function getPaymentsByUserId($userId)
    {
        try {
            // Mengambil data payments, fields, dan booking
            $payments = Bank::where('user_id', $userId)
                ->with([
                    'payments' => function ($query) {
                        $query->select(
                            'id',
                            'user_id',
                            'booking_id',
                            'total_payment',
                            'payment_id',
                            'status',
                            'order_id',
                            'receipt',
                            'created_at',
                            'updated_at',
                        )
                            ->with([
                                'field' => function ($query) {
                                    $query->select(
                                        'fields.id as field_id',
                                        'fields.name',
                                        'fields.field_centre_id',
                                    )
                                        ->with([
                                            'fieldCentre:id,name,rating,address', // Relasi field_centre
                                        ]);
                                },
                                'booking:id,field_id,booking_start,booking_end,date',
                                'user:id,name,email',
                            ]);
                    },

                ])
                ->get();

            $total_revenue = 0;
            $total_transaksi = 0;

            // Format hasil data
            $formattedPayments = $payments->flatMap(function ($bank) use (&$total_revenue, &$total_transaksi) {
                return $bank->payments->map(function ($payment) use ($bank, &$total_revenue, &$total_transaksi) {
                    if ($payment->status === "Selesai") {
                        $total_revenue += floatval($payment->total_payment);
                        $total_transaksi++;
                    }

                    return [
                        'id' => $payment->id,
                        'user_id' => $payment->user_id,
                        'booking_id' => $payment->booking_id,
                        'total_payment' => $payment->total_payment,
                        'payment_id' => $payment->payment_id,
                        'status' => $payment->status,
                        'order_id' => $payment->order_id,
                        'receipt' => $payment->receipt,
                        'field' => $payment->field,
                        'booking' => $payment->booking,
                        'user' => $payment->user,
                        'bank' => [
                            'id' => $bank->id,
                            'bank_name' => $bank->bank_name,
                            'account_number' => $bank->account_number,
                            'field_centre_id' => $bank->field_centre_id,
                        ],
                    ];
                });
            });

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved data on Payments and Bookings',
                'total_revenue' => $total_revenue,
                'total_transaksi' => $total_transaksi,
                'data' => $formattedPayments,
            ], 200);
        } catch (\Exception $e) {
            // Penanganan error
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Payments and Bookings',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        try {
            $banks = Bank::all();
            return response()->json([
                'status' => 'success',
                'data' => $banks
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve banks'
            ], 500);
        }
    }

    public function getFormData()
    {
        try {
            $data = [
                'users' => User::where('role', 'Admin Lapangan')->get(),
                'field_centres' => FieldCentre::all()
            ];
            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve form data'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'bank_name' => 'required|string',
                'account_number' => 'required|string',
                'field_centre_id' => 'required|numeric',
                'user_id' => 'required|numeric',
            ]);
            $bank = Bank::create($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Bank/Metode Pembayaran berhasil ditambahkan',
                'data' => $bank
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bank/Metode Pembayaran gagal ditambahkan'
            ], 500);
        }
    }

    public function show(Bank $bank)
    {
        try {
            return response()->json([
                'status' => 'success',
                'data' => $bank
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bank not found'
            ], 404);
        }
    }

    public function update(Request $request, Bank $bank)
    {
        try {
            $validated = $request->validate([
                'bank_name' => 'required|string',
                'account_number' => 'required|string',
                'field_centre_id' => 'required|numeric',
                'user_id' => 'required|numeric',
            ]);
            $bank->update($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Bank/Metode Pembayaran berhasil diperbarui',
                'data' => $bank
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bank/Metode Pembayaran gagal diperbarui'
            ], 500);
        }
    }

    public function destroy(Bank $bank)
    {
        try {
            $bank->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Bank/Metode Pembayaran berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bank/Metode Pembayaran gagal dihapus'
            ], 500);
        }
    }
}
