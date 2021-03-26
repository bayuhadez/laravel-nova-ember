<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class LoginService
{
	public function __construct()
	{}

	/**
	 * Set session as mark auto login from client
	 *
	 * @return void
	 */
	public static function putSessionLoginFromClientApp()
	{
		session()->put('autoLoginFromClientApp', Carbon::now()->toDateString());
	}

	/**
	 * Return string of datetime when user autoLoginFromClientApp
	 * Return null when session is not found
	 *
	 * @return string|null
	 */
	public static function getSessionLoginFromClientApp()
	{
		return session()->get('autoLoginFromClientApp');
	}

	/**
	 * Return signed url for auto login to nova product resource
	 *
	 * @return string url
	 */
	public function generateSignedAutoLoginUrlToNovaProductResource($params)
	{
		$params = Crypt::encrypt($params);

		$url = URL::temporarySignedRoute(
			'autoLoginToNovaProductResource',
			now()->addMinutes(1),
			['params' => $params]
		);

		return $url;
	}

	/**
	 * Decrypt params
	 *
	 * @return array of params after decrypted
	 */
	public function decryptParams($params)
	{
		return Crypt::decrypt($params);
	}

}
