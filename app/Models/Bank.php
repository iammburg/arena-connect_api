<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        "bank_name",
        "account_number",
        "field_centre_id",
        "user_id",
    ];

    public function fieldCentre()
    {
        return $this->belongsTo(FieldCentre::class, 'field_centre_id');
    }

    public function payments()
    {
        return $this->hasMany(Payments::class, 'payment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
