<?php

namespace App\Models;

use App\Interfaces\ImageUrlAccessorableInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model implements ImageUrlAccessorableInterface
{
	public function company()
	{
		return $this->belongsTo('App\Models\Company');
	}

	public function product()
	{
		return $this->belongsTo('App\Models\Product');
	}

	/** [Accessors] **/

	/**
	 * @return string|null of image url
	 */
	public function getImageUrlAttribute()
	{
		$url = null;

		if (!empty($this->image)) {

			if (Storage::exists($this->image)) {
				$url = Storage::url($this->image);
			} else {
				$url = Storage::disk('public')->url($this->image);
			}
		}

		return $url;
	}

}
