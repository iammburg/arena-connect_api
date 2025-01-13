<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\FieldPrice;
use App\Models\FieldSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FieldPriceScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $fields = Field::with(['prices', 'schedules'])
                          ->orderBy('id')
                          ->get();

            return response()->json([
                'status' => true,
                'message' => 'Data retrieved successfully',
                'data' => $fields
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve data', $e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'field_id' => 'required|exists:fields,id',
                'price_from' => 'required|numeric|min:0',
                'price_to' => 'required|numeric|min:0|gte:price_from',
                'date' => 'required|date|after_or_equal:today',
                'start_time' => ['required', 'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/'],
                'end_time' => ['required', 'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', 'after:start_time'],
            ], [
                'start_time.regex' => 'The start time must be in 24-hour format (HH:mm), e.g., 13:30',
                'end_time.regex' => 'The end time must be in 24-hour format (HH:mm), e.g., 14:30',
            ]);

            DB::beginTransaction();

            // Format times to ensure consistency
            $startTime = date('H:i', strtotime($validated['start_time']));
            $endTime = date('H:i', strtotime($validated['end_time']));

            $price = FieldPrice::create([
                'field_id' => $validated['field_id'],
                'price_from' => $validated['price_from'],
                'price_to' => $validated['price_to'],
            ]);

            $schedule = FieldSchedule::create([
                'field_id' => $validated['field_id'],
                'date' => $validated['date'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'is_booked' => $request->input('is_booked', false),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data created successfully',
                'data' => compact('price', 'schedule')
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create data', $e);
        }
    }

    /**
     * Store multiple schedules with prices
     */
    public function storeSchedules(Request $request)
    {
        try {
            $validated = $request->validate([
                'field_id' => 'required|exists:fields,id',
                'schedules' => 'required|array',
                'schedules.*.start_time' => ['required', 'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/'],
                'schedules.*.end_time' => ['required', 'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/'],
                'schedules.*.price' => 'required|numeric|min:0',
                'schedules.*.date' => 'required|date'
            ]);

            DB::beginTransaction();

            $results = [];
            foreach ($validated['schedules'] as $schedule) {
                // Format times to ensure consistency
                $startTime = date('H:i', strtotime($schedule['start_time']));
                $endTime = date('H:i', strtotime($schedule['end_time']));

                // Validate end time is after start time
                if (strtotime($endTime) <= strtotime($startTime)) {
                    throw ValidationException::withMessages([
                        'end_time' => ['The end time must be after the start time.']
                    ]);
                }

                $price = FieldPrice::create([
                    'field_id' => $validated['field_id'],
                    'price_from' => $schedule['price'],
                    'price_to' => $schedule['price']
                ]);

                $fieldSchedule = FieldSchedule::create([
                    'field_id' => $validated['field_id'],
                    'date' => $schedule['date'],
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_booked' => false
                ]);

                $results[] = [
                    'price' => $price,
                    'schedule' => $fieldSchedule
                ];
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Schedules and prices saved successfully',
                'data' => $results
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to save schedules and prices',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $field = Field::with(['prices', 'schedules'])->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Data retrieved successfully',
                'data' => $field
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Data not found', $e, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'price_from' => 'required|numeric|min:0',
                'price_to' => 'required|numeric|min:0|gte:price_from',
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
            ]);

            DB::beginTransaction();

            $price = FieldPrice::where('field_id', $id)->update([
                'price_from' => $validated['price_from'],
                'price_to' => $validated['price_to'],
            ]);

            $schedule = FieldSchedule::where('field_id', $id)->update([
                'date' => $validated['date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'is_booked' => $request->input('is_booked', false),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data updated successfully',
                'data' => compact('price', 'schedule')
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update data', $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            FieldPrice::where('field_id', $id)->delete();
            FieldSchedule::where('field_id', $id)->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to delete data', $e);
        }
    }

    /**
     * Handle error responses
     */
    private function errorResponse(string $message, \Exception $e, int $status = 500)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'error' => $e->getMessage()
        ], $status);
    }
}
