<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class VerifyApiEmail extends VerifyEmailBase
{
	/**
	 * Get the verification URL for the given notifiable.
	 *
	 * @param mixed $notifiable
	 * @return string url
	 */
	protected function verificationUrl($notifiable)
	{
		$now = Carbon::now();
		$userId = $notifiable->getKey();

		// update user's verification_email_sent_at
		$user = User::find($userId);
		$user->verification_email_sent_at = $now->toDateTimeString();
		$user->save();

		// cid is crypted id
		$encryptedUserId = Crypt::encrypt($userId);

		// email is encrypted
		$encryptedEmail = Crypt::encrypt($user->email);

		return URL::temporarySignedRoute(
			'verificationapi.verify',
			$now->addHours(24 * 7), // available for a week
			[
				'cid' => $encryptedUserId, // encrypted user's id
				'cem' => $encryptedEmail,  // encrypted email
			]
		); // this will basically mimic the email endpoint with get request
	}
}
