<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}
