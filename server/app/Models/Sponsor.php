<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    //
    protected $fillable = [
        'sponsor_image_path',
        'sponsor_name',
        'platinum'
    ];
}
