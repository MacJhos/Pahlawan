<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Hero extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'birth_date',
        'hometown',
        'category',
        'death_date',
        'image_path',
        'quotes',
        'bio_id',
        'bio_en'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($hero) {
            if (Auth::check()) {
                $hero->user_id = Auth::id();
            }
        });

        static::saving(function ($hero) {
            if (empty($hero->slug) || $hero->isDirty('name')) {
                $slug = Str::slug($hero->name);
                $originalSlug = $slug;
                $count = 1;

                while (static::where('slug', $slug)->where('id', '!=', $hero->id)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }

                $hero->slug = $slug;
            }
        });
    }

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return asset('images/default-hero.jpg');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
