<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FieldCentre;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'Admin Lapangan') {
            $fields = Field::with([
                'fieldCentre' => function ($query) {
                    $query->select('field_centres.id', 'field_centres.name');
                },
            ])
                ->whereHas('fieldCentre', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })
                ->get();
        } else if (Auth::user()->role == 'Admin Aplikasi') {
            $fields = Field::all();
        }
        return view('fields.index', compact('fields'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Tersedia,Telah dibooking,Dalam perbaikan,Tidak tersedia',
        ]);

        try {
            $field = Field::findOrFail($id);
            $field->status = $request->status;
            $field->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Status lapangan gagal diubah');
        }

        return redirect()->route('fields.index')->with('success', 'Status lapangan berhasil diubah');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'Admin Lapangan') {
            $field_centres = FieldCentre::where('user_id', Auth::user()->id)->get();
        } else if (Auth::user()->role == 'Admin Aplikasi') {
            $field_centres = FieldCentre::all();
        }

        return view('fields.create', compact('field_centres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'field_centre_id' => 'required|numeric',
            'type' => 'required|in:Futsal,Badminton',
            'descriptions' => 'required|string',
            'status' => 'required|boolean', // Changed to boolean validation
        ]);

        try {
            Field::create($validated);
            return redirect()->route('fields.index')->with('success', 'Lapangan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lapangan gagal ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Field $field)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Field $field)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Field $field)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Field $field)
    {
        //
    }
}
