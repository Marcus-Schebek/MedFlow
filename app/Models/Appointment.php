<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;
    protected $fillable = [
        'status',
        'doctor_id',
        'user_id',
        'date',
        'duration'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public static function getByUser($userId)
{
    return self::where('user_id', $userId)->get();
}
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
