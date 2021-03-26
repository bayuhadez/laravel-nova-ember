<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLicenseRequest;
use App\Interfaces\ApprovableInterface;
use App\Models\License;
use App\Services\LicenseService;
use Carbon\Carbon;
use CloudCreativity\LaravelJsonApi\Document\Error;
use CloudCreativity\LaravelJsonApi\Http\Controllers\JsonApiController;
use DB;
use Illuminate\Http\Request;

class MentorRequestController extends JsonApiController
{
	protected $licenseService;

	/**
	 *
	 */
	public function __construct(LicenseService $licenseService)
	{
		$this->licenseService = $licenseService;
	}

	/**
	 * Store a newly created Mentor Request (License) resource in storage.
	 *
	 * @param  \App\Http\Requests\StoreLicenseRequest  $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function storeMentorRequest(StoreLicenseRequest $request)
	{
		$user = auth('api')->user();

		// create license attribute array
		$licenseAttr = [];
		$licenseAttr['name'] = $request->input('full-name');
		$licenseAttr['number'] = $request->input('number');
		$licenseAttr['expiry_date'] = Carbon::createFromFormat('d/m/Y', $request->input('expiry-date'));
		$licenseAttr['user_id'] = $user->id;
		$licenseAttr['status'] = ApprovableInterface::STATUS_PROPOSED;

		// get uploaded photo
		$photo = $request->file('photo');

		DB::beginTransaction();

		try {
			// creat mentor request (license)
			$license = $this->licenseService
				->createLicenseFromMentorRequest($licenseAttr, $photo);

			DB::commit();

			return response()->json(
				[
					'data' => [
						'attributes' => [
							'id' => $license->id,
						],
						'type' => 'licenses',
					]
				],
				200
			);

		} catch (\Exception $ex) {

			DB::rollback();

			return $this->reply()->errors(Error::create([
				'title' => 'Unprocessable Mentor Request',
				'detail' => $ex->getMessage(),
				'status' => '422',
			]));

		}
	}
}
