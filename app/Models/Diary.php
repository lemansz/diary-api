<?php

namespace App\Models;

use Database\Factories\DiaryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

#[Fillable(['user_id', 'title', 'content', 'entry_date', 'mood', 'weather', 'is_favourite'])]

class Diary extends Model
{
    /** @use HasFactory<DiaryFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'entry_date' => 'date:Y-m-d',
            'is_favourite' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $text)
    {
        if (!$text) return $query;

        $words = array_filter(explode(' ', trim($text)));

        return $query->where(function($q) use ($words) {
            foreach ($words as $word)
                {
                    $searchTerm = "%{$word}%";
                }

                $q->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('title', 'like', $searchTerm)
                        ->orWhere('content', 'like', $searchTerm)
                        ->orWhere('weather', 'like', $searchTerm)
                        ->orWhere('mood', 'like', $searchTerm);
                });
        });
    }

    public function scopeFavourite($query, $status)
    {
        if (is_null($status)) return $query;

        return $query->where('is_favourite', filter_var($status, FILTER_VALIDATE_BOOL));
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        if ($startDate && $endDate){
            return $query->whereBetween('entry_date', [$startDate, $endDate]);
        }

        return $query;
    }

    public function scopeSortData($query, $sortBy, $direction = 'desc')
    {
        $alloweColumns = ['entry_date', 'title', 'mood', 'weather'];
        $direction    = strtolower($direction) === 'asc' ? 'asc' : 'desc';

        if (in_array($sortBy, $alloweColumns)) {
            return $query->orderBy($sortBy, $direction);
        }

        return $query->orderBy('created_at', 'desc');
    }

}
