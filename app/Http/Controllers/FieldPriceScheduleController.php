<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Field;
use App\Models\FieldPrice;
use App\Models\FieldSchedule;
use Illuminate\Support\Facades\Auth;

class FieldPriceScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'Admin Lapangan') {
            $fields = Field::with(['prices', 'schedules'])
                ->whereHas('fieldCentre', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })
                ->get()
                ->sortBy('id');
        } else if (Auth::user()->role == 'Admin Aplikasi') {
            $fields = Field::with(['prices', 'schedules'])->get()->sortBy('id');
        }
        return view('field-price-schedule.index', compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'Admin Lapangan') {
            $fields = Field::with(['prices', 'schedules'])
                ->whereHas('fieldCentre', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })
                ->get();
        } else if (Auth::user()->role == 'Admin Aplikasi') {
            $fields = Field::all();
        }

        return view('field-price-schedule.create', compact('fields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'price_from' => 'required|numeric',
            'price_to' => 'required|numeric',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        try {
            FieldPrice::create([
                'field_id' => $request->field_id,
                'price_from' => $request->price_from,
                'price_to' => $request->price_to,
            ]);

            FieldSchedule::create([
                'field_id' => $request->field_id,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'is_booked' => $request->is_booked,
            ]);

            return redirect()->route('field-price-schedules.index')->with('success', 'Jadwal dan harga lapangan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('field-price-schedules.index')->with('error', 'Jadwal dan harga lapangan gagal ditambahkan!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
