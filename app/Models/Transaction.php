<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'field-transactions'; 

    protected $fillable = [
        'user_id',
        'field_id',
        'field_centre_id',
        'payment_method',
        'status',
        'booking_start',
        'booking_end',
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fieldCentre()
    {
        return $this->belongsTo(FieldCentre::class, 'field_centre_id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
}
