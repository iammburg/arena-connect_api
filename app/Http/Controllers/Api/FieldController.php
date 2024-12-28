<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // $fields = Field::all();
            $fields = Field::with('fieldCentre:id,name')->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfully get data on Sports Fields',
                'data' => $fields,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get data on Sports Fields',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function indexByFieldCentre($fieldCentreId)
    {
        try {
            $fields = Field::where('field_centre_id', $fieldCentreId)
                ->with([
                    'prices:id,field_id,price_from,price_to',
                    'schedules:id,field_id,date,start_time,end_time,is_booked',
                    'fieldCentre:name,id',
                ])
                ->get();

            $formattedFields = $fields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'name' => $field->name,
                    'field_centre_id' => $field->fieldCentre,
                    'type' => $field->type,
                    'descriptions' => $field->descriptions,
                    'status' => $field->status,
                    'price' => $field->prices,
                    'schedules' => $field->schedules,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Successfully get data on Sports Field',
                'data' => $formattedFields,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fields',
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
        //
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
