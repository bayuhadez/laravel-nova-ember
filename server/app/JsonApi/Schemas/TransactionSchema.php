<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class TransactionSchema extends SchemaProvider
{

	/**
	 * @var string
	 */
	protected $resourceType = 'transactions';

	/**
	 * @param $resource
	 *      the domain record being serialized.
	 * @return string
	 */
	public function getId($resource)
	{
		return (string) $resource->getRouteKey();
	}

	/**
	 * @param $resource
	 *      the domain record being serialized.
	 * @return array
	 */
	public function getAttributes($resource)
	{
		$vaNumber = null;
		$instructionPdfUrl = null;

		if ($resource->status_code == 201) {

			// fetch va number
			if ($resource->payment_type == 'bank_transfer') {

				$json = json_decode($resource->raw_json);

				$vaNumber = $json->va_numbers[0]->va_number;
			}

			// fetch instructionPdfUrl
			$available_payment_type = [
				'bank_transfer',
				'echannel',
			];

			if (in_array($resource->payment_type, $available_payment_type)) {
				$instructionPdfUrl = $resource->instructionPdfUrl;
			}
		}

		return [
			'bank' => $resource->bank,
			'instruction-pdf-url' => $instructionPdfUrl,
			'order-number' => $resource->order_number,
			'payment-type' => $resource->paymentTypeText,
			'status-code' => $resource->status_code,
			'va-number' => $vaNumber,
			'created-at' => $resource->created_at->toAtomString(),
			'updated-at' => $resource->updated_at->toAtomString(),
		];
	}

}
