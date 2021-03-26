<?php

namespace App\Models;

use Laratrust\Models\LaratrustTeam;
use App\Models\Company;
use DB;

class Team extends LaratrustTeam
{
    protected $fillable = [
        'name',
    ];

    /**
     * create of get the team record for the specified company
     * @param App\Models\Company $company
     * @return Team
     */
    public static function forCompany(Company $company): Team
    {
        return Team::firstOrCreate([
            'name' => 'company-'.$company->id,
        ]);
    }
}
