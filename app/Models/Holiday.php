<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'type',
        'description'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Check if the holiday is upcoming
     */
    public function isUpcoming()
    {
        return $this->date->isFuture();
    }

    /**
     * Check if the holiday is today
     */
    public function isToday()
    {
        return $this->date->isToday();
    }

    /**
     * Format the date in a readable format
     */
    public function getFormattedDateAttribute()
    {
        return $this->date->format('F j, Y');
    }

    /**
     * Get holidays for a specific month and year
     */
    public static function getMonthlyHolidays($month, $year)
    {
        return self::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();
    }
}