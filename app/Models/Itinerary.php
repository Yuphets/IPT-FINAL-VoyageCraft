<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Itinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'start_date', 'end_date',
        'cover_image', 'cover_image_provider', 'cover_image_remote_url',
        'cover_image_author_name', 'cover_image_author_url', 'cover_image_source_url',
        'is_public',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_public' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class)->orderBy('order');
    }

    // Accessor for full image URL
    public function getCoverImageUrlAttribute(): string
    {
        if ($this->cover_image_remote_url) {
            return $this->cover_image_remote_url;
        }

        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }

        return asset('images/destinations/' . $this->coverTheme() . '.svg');
    }

    public function hasExternalCoverImage(): bool
    {
        return filled($this->cover_image_remote_url);
    }

    public function coverTheme(): string
    {
        $segments = [
            $this->title,
            $this->description,
        ];

        if ($this->relationLoaded('destinations')) {
            $segments[] = $this->destinations->pluck('name')->implode(' ');
            $segments[] = $this->destinations->pluck('location')->implode(' ');
        }

        $haystack = Str::lower(implode(' ', array_filter($segments)));

        $themes = [
            'coast' => ['beach', 'island', 'coast', 'ocean', 'sea', 'bali', 'thailand', 'santorini', 'boracay', 'phuket'],
            'city' => ['city', 'tokyo', 'new york', 'paris', 'rome', 'dubai', 'singapore', 'barcelona', 'urban', 'street'],
            'mountain' => ['mountain', 'alps', 'banff', 'fuji', 'ski', 'hike', 'trail', 'peak', 'summit'],
            'heritage' => ['culture', 'histor', 'temple', 'ancient', 'museum', 'kyoto', 'heritage', 'colosseum', 'angkor'],
            'adventure' => ['adventure', 'road trip', 'safari', 'desert', 'canyon', 'expedition', 'wild', 'camp', 'national park'],
            'culinary' => ['food', 'culinary', 'cafe', 'restaurant', 'market', 'tasting', 'dining'],
        ];

        foreach ($themes as $theme => $keywords) {
            foreach ($keywords as $keyword) {
                if (Str::contains($haystack, $keyword)) {
                    return $theme;
                }
            }
        }

        return 'voyage';
    }
}
