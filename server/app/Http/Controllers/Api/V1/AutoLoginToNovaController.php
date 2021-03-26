<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Nova\Http\Controllers\Auth\LoginController;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Laravel\Nova\Nova;

class AutoLoginToNovaController extends LoginController
{
	protected $loginService;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(LoginService $loginService)
	{
		$this->loginService = $loginService;
	}

	/**
	 * Return json that contain signed Url to nova resource products
	 *
	 * @return json with index 'redirect' that will contain string of link
	 */
	public function generateSignedRouteToResourceProducts()
	{
		$user = auth('api')->user();

		if (!$user->can('create', Product::class)) {
			if ($request->ajax()) {
				return response('Unauthorized.', 403);
			} else {
				abort(403);
			}
		}

		$params = [
			'uid' => $user->id
		];

		$url = $this
			->loginService
			->generateSignedAutoLoginUrlToNovaProductResource($params);

		return response()->json([
			'redirect' => $url,
		]);
	}

	/**
	 * Auto login and redirect to product
	 *
	 * @return Redirect
	 */
	public function autoLoginToProductResource(Request $request)
	{
		$params = $this->loginService->decryptParams($request->get('params'));

		$userId = $params['uid'];

		$user = User::find($userId);

		// login by user instance
		$this->guard()->login($user);

		$request->session()->regenerate();

		$this->loginService->putSessionLoginFromClientApp();

		$this->clearLoginAttempts($request);

		$this->authenticated($request, $this->guard()->user());

		return redirect(Nova::path().'/resources/products');
	}

}

