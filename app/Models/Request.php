<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'status'
    ];

    /**
     * Get the user that made the request
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Check if the request has been approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the request is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the request has been rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Get pending requests for a specific user
     */
    public static function getPendingForUser($userId)
    {
        return self::where('user_id', $userId)
            ->where('status', 'pending')
            ->get();
    }

    /**
     * Get all pending requests
     */
    public static function getAllPending()
    {
        return self::where('status', 'pending')
            ->with('user')
            ->get();
    }
}