<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a manager
     */
    public function isManager()
    {
        return $this->role === 'manager';
    }

    /**
     * Get the department that the user belongs to
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the attendances for the user
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    /**
     * Get the leaves for the user
     */
    public function leaves()
    {
        return $this->hasMany(Leave::class, 'user_id');
    }

    /**
     * Get the salary records for the user
     */
    public function salaries()
    {
        return $this->hasMany(Salary::class, 'user_id');
    }

    /**
     * Get the requests made by the user
     */
    public function requests()
    {
        return $this->hasMany(Request::class, 'user_id');
    }

    /**
     * Get the sent chat messages
     */
    public function sentMessages()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    /**
     * Get the received chat messages
     */
    public function receivedMessages()
    {
        return $this->hasMany(Chat::class, 'receiver_id');
    }

    /**
     * Get the user's latest attendance
     */
    public function latestAttendance()
    {
        return $this->hasOne(Attendance::class, 'user_id')
            ->latest();
    }

    /**
     * Get the user's current salary
     */
    public function currentSalary()
    {
        return $this->hasOne(Salary::class, 'user_id')
            ->latest('effective_date');
    }
}