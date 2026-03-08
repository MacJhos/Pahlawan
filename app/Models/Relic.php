<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'origin',
        'estimated_age',
        'description_id',
        'description_en',
        'image_path',
    ];
}
