<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message'
    ];

    /**
     * Get the sender of the message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the message
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Get conversation between two users
     */
    public static function getConversation($userId1, $userId2)
    {
        return self::where(function ($query) use ($userId1, $userId2) {
                $query->where('sender_id', $userId1)
                    ->where('receiver_id', $userId2);
            })
            ->orWhere(function ($query) use ($userId1, $userId2) {
                $query->where('sender_id', $userId2)
                    ->where('receiver_id', $userId1);
            })
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Get recent conversations for a user
     */
    public static function getRecentConversations($userId)
    {
        $sent = self::where('sender_id', $userId)
            ->select('receiver_id as user_id')
            ->distinct();

        return self::where('receiver_id', $userId)
            ->select('sender_id as user_id')
            ->distinct()
            ->union($sent)
            ->get();
    }
}