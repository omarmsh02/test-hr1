<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_date',
        'check_in',
        'check_out',
        'status',
        'notes'
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    /**
     * Get the user that owns the attendance record
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Calculate the total hours worked
     */
    public function getHoursWorkedAttribute()
    {
        if ($this->check_in && $this->check_out) {
            $checkIn = new \DateTime($this->check_in);
            $checkOut = new \DateTime($this->check_out);
            $interval = $checkIn->diff($checkOut);
            return $interval->h + ($interval->i / 60);
        }
        return 0;
    }

    /**
     * Check if the attendance is marked as late
     */
    public function getIsLateAttribute()
    {
        return $this->status === 'late';
    }
}