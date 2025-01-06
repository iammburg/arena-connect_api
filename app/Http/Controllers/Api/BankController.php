<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\FieldCentre;
use App\Models\User;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of banks.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Get data for creating a new bank.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Store a newly created bank.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Display the specified bank.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Update the specified bank.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Remove the specified bank.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\JsonResponse
     */
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
