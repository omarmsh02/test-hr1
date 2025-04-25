<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'status',
        'reason'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the user that owns the leave request
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Calculate the total days of leave
     */
    public function getDaysAttribute()
    {
        $startDate = new \DateTime($this->start_date);
        $endDate = new \DateTime($this->end_date);
        $interval = $startDate->diff($endDate);
        return $interval->days + 1; // Include both start and end days
    }

    /**
     * Check if the leave is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the leave is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the leave is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}