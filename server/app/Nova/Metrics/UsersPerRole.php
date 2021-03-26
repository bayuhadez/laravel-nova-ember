<?php

namespace App\Nova\Metrics;

use App\Interfaces\RoleableInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;

class UsersPerRole extends Partition
{
	/**
	 * Calculate the value of the metric.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return mixed
	 */
	public function calculate(Request $request)
	{
		// count users who have mentor role
		$mentorCountResult = User::
			whereHas('roles', function($q) {
				$q->where('name', RoleableInterface::MENTOR);
			})
			->count();

		// count users who don't have any role
		$studentCountResult = User::
			whereDoesntHave('roles')
			->count();

		return (new PartitionResult([
			'Mentor' => $mentorCountResult,
			'Student' => $studentCountResult,
		]));
	}

	/**
	 * Determine for how many minutes the metric should be cached.
	 *
	 * @return  \DateTimeInterface|\DateInterval|float|int
	 */
	public function cacheFor()
	{
		// return now()->addMinutes(5);
	}

	/**
	 * Get the URI key for the metric.
	 *
	 * @return string
	 */
	public function uriKey()
	{
		return 'users-per-role';
	}
}
