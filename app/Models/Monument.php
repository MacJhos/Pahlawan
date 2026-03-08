<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monument extends Model
{
    protected $fillable = [
        'name',
        'location',
        'image_path',
        'description_id',
        'description_en',
        'coordinate',
    ];
}
