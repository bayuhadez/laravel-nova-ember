<?php

namespace App\Services;

use App\Mail\LicenseRefusal;
use App\Mail\LicenseApproval;
use App\Models\License;
use Illuminate\Support\Facades\Mail;

class LicenseService
{
	/**
	 * Set status to Approved and set role `mentor` for Author/User of License
	 *
	 * @param App\Models\License $license
	 * @return void
	 */
	public function approveAndSetAsMentor(License &$license)
	{
		$user = auth()->user();

		// set status approved
		$license->status = License::STATUS_APPROVED;
		$license->approver_id = $user->id;
		$license->save();

		// attach role user as mentor
		$owner = $license->user;
		$owner->attachRole('mentor');
	}

	/**
	 * Return array of Status for dropdown options
	 *
	 * @return array
	 */
	public static function getStatusOptions()
	{
		return [
			License::STATUS_PROPOSED => 'Proposed',
			License::STATUS_APPROVED => 'Approved',
			License::STATUS_REJECTED => 'Rejected',
		];
	}

	/**
	 * Return array for filter options
	 *
	 * @return array
	 */
	public static function getFilterOptions()
	{
		return [
			'Proposed' => License::STATUS_PROPOSED,
			'Approved' => License::STATUS_APPROVED,
			'Rejected' => License::STATUS_REJECTED,
		];
	}

	/**
	 * Set Status to Rejected
	 * - also set comment
	 * - also set approver
	 *
	 * @param App\Models\License $license
	 * @param string $comment (optional), default `null`
	 *
	 * @return void
	 */
	public function reject(License &$license, $comment=null)
	{
		$user = auth()->user();

		// set status rejected
		$license->status = License::STATUS_REJECTED;

		if (!is_null($comment)) {
			$license->comment = $comment;
			$license->approver_id = $user->id;
		}

		$license->save();
	}

	/**
	 * Send Email Refusal to the uploader of license
	 *
	 * @param License $license
	 * @return void
	 */
	public function sendEmailRefusal(License $license)
	{
		$user = $license->user;

		Mail::to($user)->send(new LicenseRefusal($license));
	}

	/**
	 * Send Email Approval to the uploader of license
	 *
	 * @param License $license
	 * @return void
	 */
	public function sendEmailApproval(License $license)
	{
		$user = $license->user;

		Mail::to($user)->send(new LicenseApproval($license));
	}

	/**
	 * Create license with status proposed as mentor request
	 *
	 * @param array $attributes contains License's attributes
	 * @param \Illuminate\Http\UploadedFile $photo
	 *
	 * @throws \Exception if cannot upload file or cannot store data to database
	 *
	 * @return \App\Models\License
	 */
	public function createLicenseFromMentorRequest(array $attributes, \Illuminate\Http\UploadedFile $photo)
	{
		// upload file
		$filePath = $photo->store(License::$filePath, 'public');

		// get fileName from uploaded file
		if (!empty($filePath)) {
			$attributes['photo'] = $filePath;
		} else {
			throw new \Exception('Failed to upload photo.');
		}

		// create license
		$license = License::create($attributes);

		if (empty($license)) {
			throw new \Exception('Failed to create a license.');
		}

		return $license;
	}
}
