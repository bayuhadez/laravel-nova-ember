<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\UserLoggedIn;
use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class AuthController extends Controller
{
	use ResetsPasswords;

	// token name for user->createToken
	private $tokenName;

	public function __construct()
	{
		$this->tokenName = config('app.token_name');
	}

	/**
	 * Login user and create token
	 *
	 * @param  Illuminate\Http\Request $request
	 * @param  string $email
	 * @param  string $password
	 * @param  boolean $remember_me
	 *
	 * @return JSON contains [
	 *   access_token
	 *   user_id
	 *   token_type
	 *   expires_at
	 * ]
	 */
	public function login(Request $request)
	{
		$request->validate([
			'username' => 'required|string|min:4|max:30',
			'password' => 'required|string',
			'remember_me' => 'boolean'
		]);

        $field = filter_var($request->get('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($field == 'email') {
            request()->merge([$field => $request->get('username')]);
		    $credentials = request([$field, 'password']);
        } elseif ($field == 'username') {
		    $credentials = request(['username', 'password']);
        }

		if (!Auth::attempt($credentials)) {
			return response()->json(['message' => 'Unauthorized'], 401);
		}

		$user = Auth::user();

		// log the user out of all other tokens
		$user->tokens->map(function ($token) {
			$token->delete();
		});

		// let clients know about the logout possibiliy to
		// make them check auth state with api
		broadcast(new UserLoggedIn($user))->toOthers();

		$tokenResult = $user->createToken($this->tokenName);

		$token = $tokenResult->token;

		if ($request->remember_me) {
			$token->expires_at = Carbon::now()->addWeeks(1);
		}

		$token->save();

		return response()->json(
			[
				'access_token' => $tokenResult->accessToken,
                'user_id' => $user->id,
				'token_type' => 'Bearer',
				'expires_at' => Carbon::parse(
					$tokenResult->token->expires_at
				)->toDateTimeString()
			],
			Response::HTTP_OK
		);
	}

	/**
	 * Register api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required|email',
			'password' => 'required',
			'c_password' => 'required|same:password',
		]);

		if ($validator->fails()) {
			return response()->json(
				['error'=>$validator->errors()],
				401
			);
		}

		$input = $request->all();
		$input['password'] = bcrypt($input['password']);
		$user = User::create($input);

		$success['token'] = $user
			->createToken($this->tokenName)
			->accessToken;

		$success['name'] = $user->name;

		return response()->json(['success'=>$success], Response::HTTP_OK);
	}

	/**
	 * Logout user (Revoke the token)
	 *
	 * @return JSON contains [
	 *   message
	 * ]
	 */
	public function logout(Request $request)
	{
		$request->user()->token()->revoke();
		return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
	}

	public function user (Request $request) {
		return $request->user();
	}

	/**
	 * Reset the given user's password.
	 *
	 * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
	 * @param  string  $password
	 * @return void
	 */
	protected function resetPassword($user, $password)
	{
		$user->password = Hash::make($password);
		$user->save();
		event(new PasswordReset($user));
	}

	/**
	 *  Handle reset password from profile
	 *
	 *  @return Response json
	 */
	public function profileResetPassword(Request $request)
	{
		$user = auth('api')->user();

		$validator = Validator::make($request->all(), [
			'password' => 'required|min:8|confirmed',
		]);

		if ($validator->fails()) {

			return response()->json(
				['error'=>$validator->errors()],
				401
			);

		} else {

			$this->resetPassword($user, $request->input('password'));

			return response()->json(['message' => 'Password reset successfully']);
		}
	}

	public function updateUserProfile(Request $request)
	{
		$user = auth('api')->user();

		$validator = Validator::make($request->all(), [
			'user_id' => 'required',
			'first_name' => 'required',
			'last_name' => 'required',
			'address' => 'required',
			'phone' => 'required',
		]);

		if ($validator->fails()) {

			return response()->json(
				['error' => $validator->error()], 401
			);

		} else {

			$inputs = $request->all();

			$user = User::find($inputs['user_id']);
			$person = $user->person;

			if ($inputs['email'] !== $user->email) {
				// update emails if request provided
				$user->email = $inputs['email'];
				$user->save();
				// need to resend verification email to new account
				$user->sendApiEmailVerificationNotification();
			}

			$person->first_name = $inputs['first_name'];
			$person->last_name = $inputs['last_name'];
			$person->address = $inputs['address'];
			$person->phone = $inputs['phone'];
			$person->save();

			return response()->json(['message' => 'Profile successfuly updated'], 200);
		}
	}
}
