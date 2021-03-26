<?php

namespace App\Observers;

use App\Models\{
    Company,
    Team
};
use App\Lib\Functions;

class CompanyObserver
{
	/**
	 * Handle the company "creating" event.
	 *
	 * @param  \App\Models\Company  $company
	 * @return void
	 */
	public function creating(Company $company)
    {
        if (empty($company->code)) {
            $company->code = substr($company->name, 0, 3) . Functions::randomNumber(7);
        }

        if (empty($company->value_added_tax_type)) {
            $company->value_added_tax_type = Company::VALUE_ADDED_TAX_TYPE_YES;
        }

        $user = auth()->user();

        if (!empty($user)) {
            $company->created_by = $user->id;
            $company->updated_by = $user->id;
        }

        if (empty($company->division)) {
            $company->division = Company::DIVISION_NONE;
        }
	}

	/**
	 * Handle the company "updating" event.
	 *
	 * @param  \App\Models\Company  $company
	 * @return void
	 */
	public function updating(Company $company)
    {
        $user = auth()->user();

        if (!empty($user)) {
            $company->updated_by = $user->id;
        }
    }
}
