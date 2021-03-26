<?php

namespace App\Models;

use App\Interfaces\ImageUrlAccessorableInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductBanner extends Model implements ImageUrlAccessorableInterface
{
    protected $table = 'product_banners';

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

	/** [Accessors] **/

	/**
	 * @return string|null of image url
	 */
	public function getImageUrlAttribute()
	{
		$url = null;

		if (!empty($this->banner_image_path)) {

			if (Storage::exists($this->banner_image_path)) {
				$url = Storage::url($this->banner_image_path);
			} else {
				$url = Storage::disk('public')->url($this->banner_image_path);
			}
		}

		return $url;
	}

}
