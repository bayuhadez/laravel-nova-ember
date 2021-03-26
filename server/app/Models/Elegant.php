<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
use Laratrust;

abstract class Elegant extends Model
{
    /**
     * @param string attribute (or attributes?) to retrieve
     * @param mixed $permission anything accepted by Laratrust's isAbleTo method
     * first argument
     * @param Company|false $company which company to check for.
     * pass null to use this entity's own company relationship.
     * pass a Company instance to check for that company.
     * pass false to not check based on the company at all
     * @return mixed|null $this->{$attr} if logged in user has the
     * defined permission in company, otherwise null
     */
    public function getIfAllowed(
        string $attr,
        $permission,
        $company = null
    ) {
        $allowed = false;

        // use entity's own company if null
        if ($company === null) {
            $company = $this->company;
        }

        if ($company === false) {
            $allowed = Laratrust::isAbleTo($permission);
        } else {
            $allowed = Laratrust::isAbleTo($permission, Team::forCompany($company));
        }

        // return the value if allowed
        if ($allowed) {
            return $this->{$attr};
        } else {
            return null;
        }
    }
}
