<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FieldCentre;

class FieldCentreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $field_centres = FieldCentre::with(['facilities' => function ($query) {
                $query->select('facilities.name');
            }, 'fields'])->get();

        return view('field-centres.index', compact('field_centres'));
    }
    public function landingPage()
    {
        $field_centres = FieldCentre::with(['facilities' => function ($query) {
                $query->select('facilities.name');
            }, 'fields'])->get();

        return view('index', compact('field_centres'));
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
        //
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
