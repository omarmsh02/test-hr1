<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'effective_date',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'effective_date' => 'date',
    ];

    /**
     * Get the user that owns the salary record
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Format the salary with currency
     */
    public function getFormattedSalaryAttribute()
    {
        return $this->currency . ' ' . number_format($this->amount, 2);
    }

    /**
     * Check if this is the current salary
     */
    public function isCurrent()
    {
        $latest = Salary::where('user_id', $this->users_id)
            ->latest('effective_date')
            ->first();
        
        return $latest && $latest->id === $this->id;
    }
}