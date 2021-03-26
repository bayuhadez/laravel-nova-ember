<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    const TYPE_PHONE = 0;
    const TYPE_FAX = 1;

    protected $fillable = [
        'country_id',
        'number',
        'type'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['phonecode'];

    /**
     * Get the country record associated with the phone.
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function getPhonecodeAttribute(): string
    {
        return $this->country->phonecode;
    }

    public function getPhonecodeAndNumberAttribute(): string
    {
        return $this->phonecode.$this->number;
    }
}
