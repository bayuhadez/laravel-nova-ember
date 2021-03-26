<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
	/**
	 * Get Collection for Product Status Options
	 *
	 * @return Illuminate\Support\Collection with structure:
	 *   [
	 *     (int) product status => (string) product status's name
	 *   ]
	 */
	public static function getStatusOptions()
	{
		return collect([
			Product::STATUS_PROPOSED => 'Proposed',
			Product::STATUS_REJECTED => 'Rejected',
			Product::STATUS_APPROVED => 'Approved',
		]);
	}

	/**
	 * Get Collection for Product Status Filter Options
	 *
	 * @return Illuminate\Support\Collection with structure:
	 *   [
	 *     (string) product status's name => (int) product status
	 *   ]
	 */
	public static function getStatusFilterOptions()
	{
		$options = self::getStatusOptions();

		return $options->flip();
	}

}

