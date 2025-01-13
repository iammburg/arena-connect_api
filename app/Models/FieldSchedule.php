<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        "field_id",
        "date",
        "start_time",
        "end_time",
        "is_booked",
        // "session_name"  // Menambahkan kolom untuk menyimpan nama sesi
    ];

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id', 'id');
    }
}
