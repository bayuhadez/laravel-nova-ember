<?php

namespace App\Nova;

use Laravel\Nova\Fields\Text;

class UserFields
{
	/**
	 * Get the pivot fields for the relationship.
	 *
	 * @return array
	 */
	public function __invoke()
	{
		return [
			Text::make('Name'),
		];
	}
}
