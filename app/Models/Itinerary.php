<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Itinerary extends Model
{
    use HasFactory;

    protected const THEME_IMAGE_URLS = [
        'voyage' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1600&q=80',
        'coast' => 'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1600&q=80',
        'city' => 'https://images.unsplash.com/photo-1514565131-fce0801e5785?auto=format&fit=crop&w=1600&q=80',
        'mountain' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1600&q=80',
        'heritage' => 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&w=1600&q=80',
        'adventure' => 'https://images.unsplash.com/photo-1527631746610-bca00a040d60?auto=format&fit=crop&w=1600&q=80',
        'culinary' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1600&q=80',
    ];

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

        return static::themeImageUrl($this->coverTheme());
    }

    public static function themeImageUrl(string $theme = 'voyage'): string
    {
        return static::THEME_IMAGE_URLS[$theme] ?? static::THEME_IMAGE_URLS['voyage'];
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
