<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\Api\Auth;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Logika pengambilan booking berdasarkan role pengguna
            if (Auth::user()->role === 'Customer') {
                // Customer hanya melihat booking miliknya sendiri
                $bookings = Booking::with(['field', 'user'])
                    ->where('user_id', Auth::user()->id)
                    ->latest()
                    ->get();   
            } elseif (Auth::user()->role === 'Admin Lapangan') {
                // Ambil booking melalui field_centres yang dikelola user
                $bookings = Booking::with(['field', 'user', 'payments'])
                    ->whereHas('field.fieldCentre',  function($query) {
                        $query->where('user_id', Auth::user()->id);
                    })
                    ->latest()
                    ->get();
            } elseif (Auth::user()->role === 'Admin Aplikasi') {
                // Admin Aplikasi melihat semua booking
                $bookings = Booking::with(['field', 'user', 'payments'])
                    ->latest()
                    ->get();
            } else {
                // Role lain tidak memiliki akses
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin mengakses data booking'
                ], 403);
            }

            // $bookings = Booking::with('user:id,name', 'field:id,name')->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfully get data on bookings',
                'data' => $bookings,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get data on bookings',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function indexByField($fields)
    {
        try {
            $bookings = Booking::where('field_id', $fields)
                ->with([
                    'prices:field_id,price_from,price_to',
                    'schedules:field_id,date,start_time,end_time,is_booked',
                    'fieldCentre:name,id',
                    'users:id,name'

                ])
                ->get();

            $formattedBookings = $bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'user_id' => $booking->users,
                    'name' => $booking->users,
                    'field_id' => $booking->fields,
                    'booking_start' => $booking->booking_start,
                    'booking_end' => $booking->booking_end,
                    'date' => $booking->date,
                    'cost' => $booking->cost,
                    // 'schedules' => $booking->schedules,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Successfully get data on Sports Field',
                'data' => $formattedBookings,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fields',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

        // Fungsi untuk mengubah status booking (khusus Admin Lapangan)
        public function updateStatus(Request $request, $bookingId)
        {
            try {
                // Pastikan hanya Admin Lapangan yang bisa mengubah status
                if (Auth::user()->role !== 'Admin Lapangan') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki izin mengubah status booking'
                    ], 403);
                }
    
                // Cari booking
                $booking = Booking::whereHas('lapangan', function($query) {
                    $query->where('admin_lapangan_id', Auth::user()->id);
                })->findOrFail($bookingId);
    
                // Validasi status
                $validator = Validator::make($request->all(), [
                    'status' => 'required|in:Dikonfirmasi,Ditolak,Selesai'
                ]);
    
                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 400);
                }
    
                // Update status
                $booking->status = $request->status;
                $booking->save();
    
                return response()->json([
                    'success' => true,
                    'message' => 'Status booking berhasil diperbarui',
                    'data' => $booking
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui status booking: ' . $e->getMessage()
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
        $add_booking = new Booking();
        $rules = [
            'user_id' => 'required',
            'field_id' => 'required',
            'booking_start' => 'required',
            'booking_end' => 'required',
            'date' => 'required|date',
            'cost' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors(),
            ], 422);
        }

        $add_booking->user_id = $request->user_id;
        $add_booking->field_id = $request->field_id;
        $add_booking->booking_start = $request->booking_start;
        $add_booking->booking_end = $request->booking_end;
        $add_booking->date = $request->date;
        $add_booking->cost = $request->cost;

        $add_booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Add new booking successfully',
            'data' => $add_booking,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $booking = Booking::with([
                'user:id,name',
                'field:id,name,field_centre_id,type',
                'field.fieldCentre:id,name,rating,address',
            ])->find($id);

            return response()->json([
                'success' => true,
                'message' => 'Successfully get detail data on booking',
                'data' => $booking,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve booking details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
        // Fungsi untuk menghapus booking (khusus Admin Aplikasi)
        public function destroy($bookingId)
        {
            try {
                // Pastikan hanya Admin Aplikasi yang bisa menghapus
                if (Auth::user()->role !== 'Admin Aplikasi') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki izin menghapus booking'
                    ], 403);
                }
    
                // Cari dan hapus booking
                $booking = Booking::findOrFail($bookingId);
                $booking->delete();
    
                return response()->json([
                    'success' => true,
                    'message' => 'Booking berhasil dihapus'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus booking: ' . $e->getMessage()
                ], 500);
            }
        }
}
