<?php

namespace App\Nova\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laravel\Nova\Http\Controllers\LoginController as NovaLoginController;

class LoginController extends NovaLoginController
{

	/**
	 * @override
	 */
	public function __construct()
	{
		$this->middleware('nova.guest')->except([
			'logout',
			'logoutThenGoToFrontPage',
			'logoutThenGoToClientLogoutPage',
		]);
	}

	/**
	 * The user has been authenticated.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  mixed  $user
	 * @return mixed
	 */
	protected function authenticated(Request $request, $user)
	{
		// register first company into sesssion
		$firstCompany = $user->companies->first();
		$request->session()->put('company', $firstCompany);
	}

	/**
	 * Logout from Nova and then go to client front page (frontend)
	 *
	 * @return Redirect to url
	 */
	public function logoutThenGoToFrontPage(Request $request)
	{
		$this->guard()->logout();

		$request->session()->invalidate();

		$frontPageUrl = config('app.url_frontend');

		return redirect()->to($frontPageUrl);
	}

	/**
	 * Logout from Nova and then go to logout client page
	 *
	 * @return Redirect to url
	 */
	public function logoutThenGoToClientLogoutPage(Request $request)
	{
		$this->guard()->logout();

		$request->session()->invalidate();

		$clientLogoutPageUrl = config('app.url_frontend').'/logout';

		return redirect()->to($clientLogoutPageUrl);
	}

}
