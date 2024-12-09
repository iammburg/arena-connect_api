<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "field_id", "booking-start", "booking-end", "date", "cost"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    public function payments()
    {
        return $this->hasOne(Payments::class, 'booking_id', 'id');
    }
    public function fieldCentre()
    {
        return $this->belongsTo(FieldCentre::class, 'field_centre_id');
    }
}
