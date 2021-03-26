<?php

namespace App\Http\Controllers\Api\V1;

//use CloudCreativity\LaravelJsonApi\Document\Error;
use App\JsonApi\Records\Registrar;
use App\Mail\WelcomeToXxx;
use App\Models\Person;
use App\Models\User;
use CloudCreativity\LaravelJsonApi\Http\Controllers\JsonApiController;
use CloudCreativity\LaravelJsonApi\Http\Requests\CreateResource;
use Illuminate\Support\Facades\Mail;

class RegistrarsController extends JsonApiController
{
	protected $useTransactions = true;

	public function __construct()
	{
		//
	}

	public function creating(CreateResource $request)
	{
		$attributes = $request->get('data.attributes');

		$registrar = new Registrar;
		$registrar->email = $attributes['email'];
		$registrar->address = $attributes['address'];
		$registrar->first_name = $attributes['first-name'];
		$registrar->last_name = $attributes['last-name'];
		$registrar->phone = $attributes['phone'];
		$registrar->registration_certificate_number = $attributes['registration-certificate-number'];

		// create User
		$userData = [
			'email' => $attributes['email'],
			'password' => bcrypt($attributes['password']),
			'name' => $attributes['first-name'].' '.$attributes['last-name'],
		];
		$user = User::create($userData);

		// create Person
		$personData = [
			'first_name' => $attributes['first-name'],
			'last_name' => $attributes['last-name'],
			'address' => $attributes['address'],
			'phone' => $attributes['phone'],
			'user_id' => $user->id,
			'registration_certificate_number' => $attributes['registration-certificate-number'],
		];
		$person = Person::create($personData);

		// attach user to company
		$user->companies()->attach(config('app.default_company_id'));

		Mail::to($user)->queue(new WelcomeToXxx());

		// send email verification
		$user->sendApiEmailVerificationNotification();

		// create auth token
		/*
		$registrar->token = $user
			->createToken(config('app.token_name'))
			->accessToken;
		 */

		// reply content registrar
		return $this
			->reply()
			->content($registrar);
	}
}
