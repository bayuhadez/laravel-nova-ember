<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class VerificationApiController extends Controller
{
	use VerifiesEmails;

	/**
	 * Show the email verification notice.
	 *
	 */
	public function show()
	{
		//
	}

	/**
	 * Mark the authenticated user’s email address as verified.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function verify(Request $request)
	{
		$encryptedUserId = $request['cid'];
		$encryptedEmail  = $request['cem'];

		// decrypt user's Id from crypted user's id
		$userId = Crypt::decrypt($encryptedUserId);
		$userEmail = Crypt::decrypt($encryptedEmail);

		// find user
		$user = User::where('email', $userEmail)->findOrFail($userId);

		if (!$user->hasVerifiedEmail()) {

			// user has not verified email yet
			$date = Carbon::now()->toDateTimeString();
			$user->email_verified_at = $date; // to enable the email_verified_at field of that user be a current time stamp by mimicing the must verify email feature
			$user->save();

			return redirect(config('app.url_frontend').'/landing/verified-email');

		} else {

			// user has verified email before
			// we don't need to update email_verified_at data
			// directly redirect to landing page that provide the information

			return redirect(config('app.url_frontend').'/landing/already-verified-email');
		}
	}

	/**
	 * Resend the email verification notification.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function resend(Request $request)
	{
		$user = $request->user();

		if ($user->hasVerifiedEmail()) {
			return response()->json('User already have verified email!', 422);
		} elseif (
			!empty($user->verification_email_sent_at) &&
			!$user->isMoreThanTenMinutesAfterLastVerificationEmailSent()
		) {
			return response()->json(
				"You just resend verification email, please wait 10 minutes. ".
				"Please check your inbox or spam on your email",
				422
			);
		}

		$user->sendApiEmailVerificationNotification();

		return response()->json("The verification email has been resend, please check you inbox or spam");
	}

}
