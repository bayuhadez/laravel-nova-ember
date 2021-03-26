<?php

namespace App\Http\Controllers;

use Artisan;
use Illuminate\Http\Request;

class ArtisanContoller extends Controller
{
	public function getClearAllCache()
	{
		$user = Auth::user();

		if ($user->hasRole('superadministrator')) {

			$responses = [];

			/*
			$exitCode = Artisan::call('optimize');
			$responses[] = '<h1>Reoptimized class loader</h1>';
			*/

			$exitCode = Artisan::call('cache:clear');
			$responses[] = '<h1>Cache facade value cleared</h1>';

			$exitCode = Artisan::call('view:clear');
			$responses[] = '<h1>View cache cleared</h1>';

			$exitCode = Artisan::call('route:cache');
			$responses[] = '<h1>Routes cached</h1>';

			$exitCode = Artisan::call('config:cache');
			$responses[] = '<h1>Clear Config cleared</h1>';

			return implode('<br/>', $responses);

		}
	}
}
