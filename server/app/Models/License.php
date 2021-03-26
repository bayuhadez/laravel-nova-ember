<?php

namespace App\Models;

use App\Interfaces\ApprovableInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model implements ApprovableInterface
{
	use SoftDeletes;

	protected $fillable = [
		'expiry_date',
		'name',
		'number',
		'photo',
		'user_id',
	];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
		'expiry_date',
	];

	/**
	 * The attibute for directory name
	 */
	public static $filePath = 'licenses';

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
}
