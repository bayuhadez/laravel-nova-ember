<?php

namespace App\Observers;

use App\Lib\Functions;
use App\Models\User;
use App\Models\Company;

class UserObserver
{
	/**
	 * Handle the user "created" event.
	 *
	 * @param  \App\User  $user
	 * @return void
	 */
	public function created(User $user)
	{
		// get logged in user
		$currentUser = auth()->user() ?? null;
		$companyFromSession = session('company') ?? null;

		if (!empty($currentUser) && !$currentUser->hasRole('superadministrator')) {

			$companyIds = null;

			// use company from session to attach user into the company
			if ($companyFromSession) {

				$companyIds = collect($companyFromSession->id);

			} else { // if no company from session

				// attach all user's companies
				$companyIds = $user->companies->pluck('id');

			}

			if (!empty($companyIds)) {
				$user->companies()->attach($companyIds->all());
			}

        }
        
        // check if user created password is not hashed with bcrypt
        if (!Functions::isBcrypted($user->password)) {

            // bcrypt current user password
            $user->password = bcrypt($user->password);
            $user->save();

            // user from api to be default assigned to first company
            $company = Company::first();
            $user->companies()->attach($company);
        }
	}

	/**
	 * Handle the user "updated" event.
	 *
	 * @param  \App\User  $user
	 * @return void
	 */
	public function updated(User $user)
	{
		//
	}

    /**
     * Handle the user updating event
     *
     * @param \App\Models\User
     * @return void
     */
	public function updating(User $user)
	{
        if (!empty($user->password) && !Functions::isBcrypted($user->password)) {
            $user->password = bcrypt($user->password);
        }
	}

	/**
	 * Handle the user "deleted" event.
	 *
	 * @param  \App\User  $user
	 * @return void
	 */
	public function deleted(User $user)
	{
		//
	}

	/**
	 * Handle the user "restored" event.
	 *
	 * @param  \App\User  $user
	 * @return void
	 */
	public function restored(User $user)
	{
		//
	}

	/**
	 * Handle the user "force deleted" event.
	 *
	 * @param  \App\User  $user
	 * @return void
	 */
	public function forceDeleted(User $user)
	{
		//
	}
}
