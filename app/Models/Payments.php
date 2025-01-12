<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "booking_id",
        "total_payment",
        "payment_id",
        "status",
        "order_id",
        "receipt",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
    public function field()
    {
        return $this->hasOneThrough(Field::class, Booking::class, 'id', 'id', 'booking_id', 'field_id')->select('fields.id as field_id', 'fields.name', 'fields.field_centre_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'payment_id');
    }
}
