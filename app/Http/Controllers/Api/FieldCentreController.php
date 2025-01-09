<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FieldCentre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FieldCentreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $field_centres = FieldCentre::with([
                'facilities' => function ($query) {
                    $query->select('facilities.name');
                },
                'fields',
            ])->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfully get data on Sports Field Centres',
                'data' => $field_centres,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get data on Sports Field Centres',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $add_field_centres = new FieldCentre();
        $rules = [
            'user_id' => 'required',
            'name' => 'required',
            'descriptions' => 'required',
            'rules' => 'required',
            'address' => 'required',
            'maps' => 'required',
            'phone_number' => 'required',
            'price_from' => 'required',
            // 'facilities' => 'required',
            'facility_ids' => 'required|array',
            'facility_ids.*' => 'exists:facilities,id',
            'rating' => 'required|numeric|min:0|max:5',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                $imagePaths[] = url('storage/' . $path);
            }
        }

        $add_field_centres->user_id = $request->user_id;
        $add_field_centres->name = $request->name;
        $add_field_centres->descriptions = $request->descriptions;
        $add_field_centres->rules = $request->rules;
        $add_field_centres->address = $request->address;
        $add_field_centres->maps = $request->maps;
        $add_field_centres->phone_number = $request->phone_number;
        $add_field_centres->price_from = $request->price_from;
        // $add_field_centres->facilities = $request->facilities;
        $add_field_centres->rating = $request->rating;
        $add_field_centres->images = json_encode($imagePaths, JSON_UNESCAPED_SLASHES);

        $add_field_centres->save();
        $add_field_centres->facilities()->sync($request->facility_ids);

        return response()->json([
            'success' => true,
            'message' => 'Add new field centre successfully',
            'data' => $add_field_centres->load('facilities'),
        ], 201);
    }

    public function getFieldByUserId($userId)
    {
        try {
            // Ambil data FieldCentre berdasarkan user_id
            $fields = FieldCentre::where('user_id', $userId)
                ->with([
                    'fields' => function ($query) {
                        $query->select('id', 'name', 'field_centre_id', 'type', 'descriptions', 'status');
                    },
                    'fields.prices' => function ($query) {
                        $query->select('field_id', 'price_from', 'price_to');
                    },
                    'fields.schedules' => function ($query) {
                        $query->select('field_id', 'date', 'start_time', 'end_time', 'is_booked');
                    },
                ])
                ->get();

            // Format hasil data
            $formattedFields = $fields->flatMap(function ($fieldCentre) {
                return $fieldCentre->fields->map(function ($field) use ($fieldCentre) {
                    return [
                        'id' => $field->id,
                        'name' => $field->name,
                        'field_centre_id' => $field->field_centre_id,
                        'type' => $field->type,
                        'descriptions' => $field->descriptions,
                        'status' => $field->status,
                        'price' => $field->prices,
                        'schedules' => $field->schedules,
                        'address' => $fieldCentre->address,
                        'price_from' => $fieldCentre->price_from,
                        'images.*' => $fieldCentre->images,
                    ];
                });
            });

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved data on Sports Fields',
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
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $field_centre = FieldCentre::with([
                'user',
                'facilities' => function ($query) {
                    $query->select('facilities.name');
                }
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Successfully get detail data on Sports Field Centre',
                'data' => $field_centre,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve field centre details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FieldCentre $fieldCentre)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FieldCentre $fieldCentre)
    {
        //
    }
}
