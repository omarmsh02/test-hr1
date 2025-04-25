<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active policies
     */
    public static function getActive()
    {
        return self::where('is_active', true)->get();
    }

    /**
     * Get policies by category
     */
    public static function getByCategory($category)
    {
        return self::where('category', $category)
            ->where('is_active', true)
            ->get();
    }
}