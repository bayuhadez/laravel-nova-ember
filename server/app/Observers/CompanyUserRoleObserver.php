<?php

namespace App\Observers;

use App\Models\CompanyUserRole;
use App\Models\Team;

class CompanyUserRoleObserver
{
    /**
     * Handle the company user role "saved" event.
     *
     * @param  \App\Models\CompanyUserRole  $companyUserRole
     * @return void
     */
    public function saved(CompanyUserRole $companyUserRole)
    {
        $company = $companyUserRole->companyUser->company;

        // get the team for the company
        $team = Team::forCompany($company);

        // create the role for laratrust team
        $companyUserRole
            ->companyUser
            ->user
            ->attachRole(
                $companyUserRole->role,
                $team
            );
    }

    /**
     * Handle the company user role "updated" event.
     *
     * @param  \App\Models\CompanyUserRole  $companyUserRole
     * @return void
     */
    public function updated(CompanyUserRole $companyUserRole)
    {
        //
    }

    /**
     * Handle the company user role "deleted" event.
     *
     * @param  \App\Models\CompanyUserRole  $companyUserRole
     * @return void
     */
    public function deleted(CompanyUserRole $companyUserRole)
    {
        $company = $companyUserRole->companyUser->company;

        // get the team for the company
        $team = Team::forCompany($company);

        // detach the role for laratrust team
        $companyUserRole
            ->companyUser
            ->user
            ->detachRole(
                $companyUserRole->role,
                $team
            );
    }

    /**
     * Handle the company user role "restored" event.
     *
     * @param  \App\Models\CompanyUserRole  $companyUserRole
     * @return void
     */
    public function restored(CompanyUserRole $companyUserRole)
    {
        //
    }

    /**
     * Handle the company user role "force deleted" event.
     *
     * @param  \App\Models\CompanyUserRole  $companyUserRole
     * @return void
     */
    public function forceDeleted(CompanyUserRole $companyUserRole)
    {
        //
    }
}
