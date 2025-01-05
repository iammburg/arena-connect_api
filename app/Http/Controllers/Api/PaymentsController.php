<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Api\DB;

class PaymentsController extends Controller
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
                $payments = Payments::with(['field', 'user'])
                    ->where('user_id', Auth::user()->id)
                    ->latest()
                    ->get();   
            } elseif (Auth::user()->role === 'Admin Lapangan') {
                // Ambil booking melalui field_centres yang dikelola user
                $payments = Payments::with(['field', 'user', 'booking'])
                    ->whereHas('field.fieldCentre', function($query) {
                        $query->where('user_id', Auth::user()->id);
                    })
                    ->latest()
                    ->get();
            } elseif (Auth::user()->role === 'Admin Aplikasi') {
                // Admin Aplikasi melihat semua booking
                $payments = Payments::with(['field', 'user', 'booking'])
                    ->latest()
                    ->get();
            } else {
                // Role lain tidak memiliki akses
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin mengakses data booking'
                ], 403);
            }

            // $payments = Payments::with([
            //     'field' => function ($query) {
            //         $query->select('fields.id as field_id', 'fields.name', 'fields.field_centre_id')
            //             ->with([
            //                 'fieldCentre:id,name,rating,address',
            //             ]);
            //     },

            //     'booking' => function ($query) {
            //         $query->select('id', 'field_id', 'booking_start', 'booking_end', 'date');
            //     },
            //     'user:id,name,email',
            // ])->get();

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
            'receipt.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
                $path = $image->store('receipt', 'public');
                $imagePaths[] = url('storage/' . $path);
            }
        }

        $add_payments->user_id = $request->user_id;
        $add_payments->booking_id = $request->booking_id;
        $add_payments->total_payment = $request->total_payment;
        $add_payments->payment_method = $request->payment_method;
        $add_payments->status = $request->status;
        $add_payments->order_id = $request->order_id;
        $add_payments->receipt = json_encode($imagePaths, JSON_UNESCAPED_SLASHES);

        $add_payments->save();
        // //Validate Form
        // $request->validate([
        //     'user_id' => 'required',
        //     'booking_id' => 'required',
        //     'total_payment' => 'required',
        //     'payment_method' => 'required',
        //     'status' => 'required',
        //     'order_id' => 'required|numeric|min:0',
        //     'receipt.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        // ]);

        // //UPLOAD Image
        // $receipt = $request->file('receipt');
        // $receipt->storeAs('public/receipts', $receipt->hashName());

        // //Create Payments
        // Payments::create([
        //     'user_id' => $request->user_id,
        //     'booking_id' => $request->booking_id,
        //     'total_payment' => $request->total_payment,
        //     'payment_method' => $request->payment_method,
        //     'status' => $request->status,
        //     'order_id' => $request->order_id,
        //     'receipt' => $receipt->hashName(),
        // ]);
        // $payments = Payments::all();

        return response()->json([
            'success' => true,
            'message' => 'Add new Payments successfully',
            'data' => $add_payments,
        ], 201);

        // //Validate Form
        // $request->validate([
        //     'user_id' => 'required',
        //     'booking_id' => 'required',
        //     'total_payment' => 'required',
        //     'payment_method' => 'required',
        //     'status' => 'required',
        //     'order_id' => 'required|numeric|min:0',
        //     'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        // ]);

        // //Check if Image is Uploaded
        // if ($request->hasFile('receipt')) {
        //     //Upload Image
        //     $receipt = $request->file('receipt');
        //     $receipt->storeAs('public/receipts', $receipt->hashName());

        //     //Create Payments with Image
        //     $payment = Payments::create([
        //         'user_id' => $request->user_id,
        //         'booking_id' => $request->booking_id,
        //         'total_payment' => $request->total_payment,
        //         'payment_method' => $request->payment_method,
        //         'status' => $request->status,
        //         'order_id' => $request->order_id,
        //         'receipt' => $receipt->hashName(),
        //     ]);
            
        // } 
        // $payments = Payments::ll();
        // // else {
        // //     //Create Payments without Image
        // //     $payment = Payments::create([
        // //         'user_id' => $request->user_id,
        // //         'booking_id' => $request->booking_id,
        // //         'total_payment' => $request->total_payment,
        // //         'payment_method' => $request->payment_method,
        // //         'status' => $request->status,
        // //         'order_id' => $request->order_id,
        // //     ]);
        // // }

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Add new Payments successfully',
        //     'data' => $payment,
        // ], 201);
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
            // Dapatkan data payment berdasarkan ID
            $payment = Payments::findOrFail($id);
            $payments = Payments::with(['user', 'field.fieldCentre', 'booking'])->get();
    
            // Ambil input dan validasi hanya field yang ada
            $validatedData = $request->validate([
                'user_id' => 'sometimes|required',
                'booking_id' => 'sometimes|required',
                'total_payment' => 'sometimes|required',
                'payment_method' => 'sometimes|required',
                'status' => 'sometimes|required',
                'order_id' => 'sometimes|required|numeric|min:0',
                'receipt.*' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'date' => 'sometimes|required|date',
            ]);
    
            // Perbarui field yang diberikan
            $payment->update($validatedData);
    
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
        // try {
        //     //Validate Form
        //     $request->validate([
        //         'user_id' => 'required',
        //         'booking_id' => 'required',
        //         'total_payment' => 'required',
        //         'payment_method' => 'required',
        //         'status' => 'required',
        //         'order_id' => 'required|numeric|min:0',
        //         'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        //     ]);

        //     //Get Product by ID
        //     $payment = Payments::findOrFail($id);

        //     //Check if Image is Uploaded
        //     if ($request->hasFile('receipt')) {
        //         //Upload New Image
        //         $receipt = $request->file('receipt');
        //         $receipt->storeAs('public/receipts', $receipt->hashName());

        //         //Delete Old Image
        //         Storage::delete("public/receipts/{$payment->receipt}");

        //         //Update Product with new Image
        //         $payment->update([
        //             'user_id' => $request->user_id,
        //             'booking_id' => $request->booking_id,
        //             'total_payment' => $request->total_payment,
        //             'payment_method' => $request->payment_method,
        //             'status' => $request->status,
        //             'order_id' => $request->order_id,
        //             'receipt' => $receipt->hashName(),
        //         ]);
        //     } else {
        //         //Update Payment without Image
        //         $payment->update([
        //             'user_id' => $request->user_id,
        //             'booking_id' => $request->booking_id,
        //             'total_payment' => $request->total_payment,
        //             'payment_method' => $request->payment_method,
        //             'status' => $request->status,
        //             'order_id' => $request->order_id,
        //         ]);
        //     }

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Payment updated successfully',
        //         'data' => $payment,
        //     ], 200);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to update payment',
        //         'error' => $e->getMessage(),
        //     ], 500);
        // }
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
                'errors' => $validator->errors()
            ], 422);
        }

        // Temukan payment berdasarkan ID
        $payment = Payments::findOrFail($id);

        // Update status
        $payment->status = $request->input('status');
        $payment->save();

        // Muat relasi yang mungkin diperlukan
        $payment->load('user', 'field', 'booking');

        // Berikan respons
        return response()->json([
            'message' => 'Payment updated successfully',
            'data' => $payment
        ], 200);
    } catch (ModelNotFoundException $e) {
        // Tangani jika payment tidak ditemukan
        return response()->json([
            'message' => 'Payment not found',
            'error' => $e->getMessage()
        ], 404);
    } catch (\Exception $e) {
        // Tangani error umum
        return response()->json([
            'message' => 'Error updating payment',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getTotalRevenue()
{
    // Validasi dan keamanan tambahan
    try {
        // Menggunakan model Payments untuk perhitungan total
        $totalRevenue = Payments::where('status','selesai')->sum('total_payment');
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


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments $payments)
    {
        //
    }
}

// class PaymentsController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      */
//     public function index()
//     {
//         try {
//             $payments = Payments::with('user:id,name', 'booking:id,field_id', 'field:id,name')->get();

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Successfully get data on Payments Status',
//                 'data' => $payments,
//             ], 200);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to get data on Payments Status',
//                 'error' => $e->getMessage(),
//             ], 500);
//         }
//     }

//     // public function indexByBooking($booking)
//     // {
//     //     try {
//     //         $payment = Payments::where('booking_id', $booking)
//     //             ->with([
//     //                 // 'prices:field_id,price_from,price_to',
//     //                 // 'schedules:field_id,date,start_time,end_time,is_booked',
//     //                 // 'fieldCentre:name,id',
//     //                 'users:id,name',
//     //                 'booking:id,name'

//     //             ])
//     //             ->get();

//     //         $formattedPayments = $payment->map(function ($payment) {
//     //             return [
//     //                 'id' => $payment->id,
//     //                 'user_id' => $payment->users,
//     //                 'name' => $payment->users,
//     //                 'field_id' => $payment->booking,
//     //                 'booking_start' => $payment->booking,
//     //                 'booking_end' => $payment->booking,
//     //                 'date' => $payment->date,
//     //                 'cost' => $payment->cost,
//     //                 'total_payment' => $payment->total_payment,
//     //                 'payment_method' => $payment->paymenr_method,
//     //                 'end_time' => $payment->end_time,
//     //                 'status' => $payment->status,
//     //                 'order_id' => $payment->orer_id,
//     //                 'payment_code' => $payment->payment_code,
//     //                 'receipt' => $payment->receipt,
//     //                 // 'schedules' => $booking->schedules,
//     //             ];
//     //         });

//     //         return response()->json([
//     //             'success' => true,
//     //             'message' => 'Successfully get data on Sports Field',
//     //             'data' => $formattedPayments,
//     //         ], 200);
//     //     } catch (\Exception $e) {
//     //         return response()->json([
//     //             'success' => false,
//     //             'message' => 'Failed to retrieve fields',
//     //             'error' => $e->getMessage(),
//     //         ], 500);
//     //     }
//     // }

//     public function indexByBooking($booking)
// {
//     try {
//         $payment = Payments::where('booking_id', $booking)
//             ->with([
//                 'users:id,name',
//                 'booking:id,name,field_id,user_id',
//                 'booking.field:id,name',// Tambahkan relasi field pada booking
//                 'booking.field.fieldCentre:id.name'
//             ])
//             ->get();

//         $formattedPayments = $payment->map(function ($payment) {
//             return [
//                 'id' => $payment->id,
//                 'user_id' => $payment->user_id, // Gunakan user_id langsung
//                 'user_name' => $payment->users ? $payment->users->name : null, // Ambil nama user
//                 'booking_id' => $payment->booking_id,
//                 'booking_name' => $payment->booking ? $payment->booking->name : null,
//                 'field_id' => $payment->booking ? $payment->booking->field_id : null, // Ambil field_id dari booking
//                 'field_name' => $payment->booking->field ? $payment->booking->field->name : null, // Ambil nama field
//                 'booking_start' => $payment->booking ? $payment->booking->booking_start : null,
//                 'booking_end' => $payment->booking ? $payment->booking->booking_end : null,
//                 'date' => $payment->date,
//                 'cost' => $payment->cost,
//                 'total_payment' => $payment->total_payment,
//                 'payment_method' => $payment->payment_method,
//                 'status' => $payment->status,
//                 'order_id' => $payment->order_id,
//                 'payment_code' => $payment->payment_code,
//                 'receipt' => $payment->receipt,
//             ];
//         });

//         return response()->json([
//             'success' => true,
//             'message' => 'Successfully get data on Sports Field Payments',
//             'data' => $formattedPayments,
//         ], 200);
//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to retrieve payments',
//             'error' => $e->getMessage(),
//         ], 500);
//     }
// }

//     /**
//      * Show the form for creating a new resource.
//      */
//     public function create()
//     {
//         //
//     }

//     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(Request $request)
//     {
//         $add_payments = new Payments();
//         $rules = [
//             'user_id' => 'required',
//             'booking_id' => 'required',
//             'total_payment' => 'required',
//             'payment_method' => 'required',
//             'status' => 'required',
//             'order_id' => 'required|numeric|min:0',
//             'payment_code' => 'required|numeric|min:0',
//             'receipt.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
//             'date' => 'required|date',
//         ];

//         $validator = Validator::make($request->all(), $rules);
//         if ($validator->fails()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Validation errors',
//                 'data' => $validator->errors(),
//             ], 422);
//         }

//         $imagePaths = [];

//         if ($request->hasFile('receipt')) {
//             foreach ($request->file('receipt') as $image) {
//                 $path = $image->store('images', 'public');
//                 $imagePaths[] = url('storage/' . $path);
//             }
//         }

//         $add_payments->user_id = $request->user_id;
//         $add_payments->booking_id = $request->booking_id;
//         $add_payments->total_payment = $request->total_payment;
//         $add_payments->payment_method = $request->payment_method;
//         $add_payments->status = $request->status;
//         $add_payments->order_id = $request->order_id;
//         $add_payments->payment_code = $request->payment_code;
//         $add_payments->receipt = json_encode($imagePaths, JSON_UNESCAPED_SLASHES);
//         $add_payments->date = $request->date;

//         $add_payments->save();

//         return response()->json([
//             'success' => true,
//             'message' => 'Add new Payments successfully',
//             'data' => $add_payments,
//         ], 201);
//     }

//     /**
//      * Display the specified resource.
//      */
//     public function show($id)
//     {
//         try {
//             // Mengambil data payment dengan table relasi
//             $payment = Payments::with([
//                 'user:id,name',
//                 'booking:id,user_id,field_id,booking_start,booking_end,date,cost',
//                 'booking.field:id,name,field_centre_id,type,descriptions,status'
//             ])->find($id);
//             // Temukan data berdasarkan ID
//             // $payment = Payments::findOrFail($id);
    
//             return response()->json([
//                 'success' => true,
//                 'message' => 'Successfully retrieved payment details',
//                 'data' => $payment,
//             ], 200);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to retrieve payment details',
//                 'error' => $e->getMessage(),
//             ], 404);
//         }
//     }

//     /**
//      * Show the form for editing the specified resource.
//      */
//     public function edit(Payments $payments)
//     {
//         //
//     }

//     /**
//      * Update the specified resource in storage.
//      */
//     public function update(Request $request,  $id)
//     {
//         try{
//             // Aturan validasi
//             $rules = [
//                 'user_id' => 'required',
//                 'booking_id' => 'required',
//                 'total_payment' => 'required',
//                 'payment_method' => 'required',
//                 'status' => 'required',
//                 'order_id' => 'required|numeric|min:0',
//                 'payment_code' => 'required|numeric|min:0',
//                 'receipt.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
//                 'date' => 'required|date',
//             ];
            
//             // Validasi input
//             $validator = Validator::make($request->all(),$rules);
//             if($validator->fails()){
//                 \Log::error('Validation Errors:', $validator->errors()->toArray());
//                 return response()->json([
//                     'success' =>false,
//                     'message'=>'Validation errors',
//                     'data' => $validator->errors(),
//                 ],422);
//             }
//             // Temukan data berdasarkan ID
//             $payment = Payments::findOrFail($id);
            
//             // // Mengelola file receipt jika ada
//             $imagePaths = [];

//             if ($request->hasFile('receipt')) {
//                 foreach ($request->file('receipt') as $image) {
//                     $path = $image->store('images', 'public');
//                     $imagePaths[] = url('storage/' . $path);
//                 }
//             }

//             // Update data Payment secara langsung
//             $payment->update([
//                 'user_id' => $request->user_id,
//                 'booking_id' => $request->booking_id,
//                 'total_payment' => $request->total_payment,
//                 'payment_method' => $request->payment_method,
//                 'status' => $request->status,
//                 'order_id' => $request->order_id,
//                 'payment_code' => $request->payment_code,
//                 'receipt' => !empty($imagePaths) ? json_encode($imagePaths, JSON_UNESCAPED_SLASHES) : $payment->receipt, // Hanya jika ada gambar yang diunggah
//                 'date' => $request->date,
//             ]);

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Payment updated successfully',
//                 'data' => $payment,
//             ], 200);
                
//         }catch(\Exception $e){
//             \Log::error('Payment Update Failed:', ['error' => $e->getMessage()]);
//             return response()->json([
//                 'success' =>false,
//                 'message' => 'Failed to update payment',
//                 'error' => $e->getMessage(),
//             ],500);
//         }   
//     }

//     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy(Payments $payments)
//     {
//         //
//     }
// }
