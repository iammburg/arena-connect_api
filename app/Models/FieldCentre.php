<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldCentre extends Model
{
    use HasFactory;
    protected $fillable = ["user_id", "name", "descriptions", "rules", "address", "maps", "phone_number", "price_from", "rating", "images"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'field_centre_facility');
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function banks()
    {
        return $this->hasMany(Bank::class, 'field_centre_id');
    }

}
