<?php
// app/Models/DonorQuestion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'question',
        'type',
        'is_disqualifying',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_disqualifying' => 'boolean',
        'is_active' => 'boolean'
    ];

    public static function getQuestionsByCategory($category)
    {
        return self::where('category', $category)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    public static function getAllQuestions()
    {
        return self::where('is_active', true)
            ->orderBy('category')
            ->orderBy('order')
            ->get()
            ->groupBy('category');
    }
}
