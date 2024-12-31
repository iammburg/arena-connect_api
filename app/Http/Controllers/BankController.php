<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FieldCentre;
use App\Models\User;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'Admin Lapangan') {
            $banks = Bank::whereHas('user', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })->get();
        } else if (Auth::user()->role == 'Admin Aplikasi') {
            $banks = Bank::all();
        }
        return view('banks.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'Admin Lapangan') {
            $user = Auth::user()->id;
            $field_centres = FieldCentre::where('user_id', Auth::user()->id)->get();
        } else if (Auth::user()->role == 'Admin Aplikasi') {
            $users = User::where('role', 'Admin Lapangan')->get();
            $field_centres = FieldCentre::all();
        }

        return view('banks.create', compact('field_centres', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'field_centre_id' => 'required|numeric',
            'user_id' => 'required|numeric',
        ]);

        try {
            Bank::create($validated);
            return redirect()->route('banks.index')->with('success', 'Bank/Metode Pembayaran berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bank/Metode Pembayaran gagal ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bank $bank)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        //
    }
}
