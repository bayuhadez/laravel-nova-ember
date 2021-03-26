<?php

namespace App\Services;

use App\Models\ProductCategory;

class CompanyService
{
	/**
	 * Get Collection for ProductCategory Membership
	 *
	 * @param integer $companyId
	 * @return Illuminate\Support\Collection with structure:
	 *   [
	 *     (int) productCategoryId => (string) productCategory's name
	 *   ]
	 */
	public static function getProductCategoryMembershipOptions($companyId)
	{
		return ProductCategory::
			where('company_id', $companyId)
			->get(['id', 'name', 'company_id'])
			->pluck('name', 'id');
	}

}
