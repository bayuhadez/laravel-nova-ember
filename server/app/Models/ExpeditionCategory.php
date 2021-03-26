<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpeditionCategory extends Model
{
    protected $table = 'expedition_categories';

    protected $fillable = ['name'];
}
