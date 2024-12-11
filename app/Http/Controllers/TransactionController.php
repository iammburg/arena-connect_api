<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\FieldCentre;
use App\Models\Field;

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar transaksi.
     */
    public function index()
{
    // Mengambil data transaksi dengan relasi
    $transactions = Transaction::with(['user', 'fieldCentre', 'field'])->get();

    return view('field-transactions.index', compact('transactions'));
}

public function edit($id)
{
    $transaction = Transaction::with(['user', 'fieldCentre', 'field'])->findOrFail($id);

    return view('field-transactions.edit', compact('transaction'));
}


    /**
     * Memperbarui transaksi.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'status' => $request->input('status'),
        ]);

        return redirect()->route('field-transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }
}
