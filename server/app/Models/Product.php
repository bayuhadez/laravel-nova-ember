<?php

namespace App\Models;

use App\Interfaces\ApprovableInterface;
use App\Interfaces\ImageUrlAccessorableInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Log;
use Illuminate\Support\Str;
use DB;
use \Imagick;
use App\Lib\Functions;

class Product extends Elegant implements ApprovableInterface, ImageUrlAccessorableInterface
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public static $fileDirectoryPrefix = 'Product';
    public static $fileDirectoryPrefixTmp = 'Product/tmp';
    public static $disk = 's3';

    protected $fillable = [
        'name',
        'description',
        'price',
        'base_price',
        'status',
        'stock',
        'maximum_stock',
        'minimum_stock',
        'code',
        'barcode',
        //'company_id',
        //'product_category_id',
    ];

    public function chatRooms()
    {
        return $this->hasMany('App\Models\ChatRoom');
    }

    public function productCategories()
    {
        return $this->belongsToMany('App\Models\ProductCategory')
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function users()
    {
        return $this
            ->belongsToMany('App\Models\User')
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order', 'order_details', 'product_id', 'order_id');
    }

    public function orderDetails()
    {
        return $this->hasMany('App\Models\OrderDetail');
    }

    public function seminarProductMeta()
    {
        return $this->hasOne('App\Models\SeminarProductMeta');
    }

    public function productBanner()
    {
        return $this->hasOne('App\Models\ProductBanner');
    }

    public function productTransactionReceipts()
    {
        return $this->hasMany('App\Models\ProductTransactionReceipt');
    }

    public function isOwnedBy($user)
    {
        return $this->user_id == $user->id;
    }

    /**
     * creates a ChatRoom related with the product
     *
     * @return ChatRoom the created ChatRoom model instance
     */
    public function createChatRoom()
    {
        return $this->chatRooms()->create();
    }

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

    public function getThumbnailUrlAttribute()
    {
        $url = null;

        if(!empty($this->thumbnailImage)) {
            if(Storage::exists($this->thumbnailImage)) {
                $url = Storage::url($this->thumbnailImage);
            } else {
                $url = Storage::disk('public')->url($this->thumbnailImage);
            }
        }

        return $url;
    }

    /**
     * given a base64 encoded string $file, upload it to s3
     */
    public function uploadImageFile(string $files)
    {
        // TODO move to global helpers file
        // get the extension of the base64 file
        $ext = explode('/', mime_content_type($files))[1];
        // decode the actual content of the uploaded base64 file
        $file = base64_decode(explode('base64,', $files)[1]);
        // create random filename followed by the extension
        $fileName = Str::random(10).'.'.$ext;

        // path where the file will be uploaded
        $filePath = self::$fileDirectoryPrefix.'/'.$this->id.'/'.$fileName;

        Storage::disk($this->disk)->put($filePath, $file);

        $this->image = $filePath;

        $this->generateThumbnailFromImage();

        $this->save();
    }

    /**
     * generates a thumbnail image file for the product based on the image file
     *
     * @return void
     */
    public function generateThumbnailFromImage()
    {
        $filePath = $this->thumbnailImage;

        // generate thumbnail image
        $image = new Imagick($this->imageUrl);
        $image->scaleImage(100, 0);
        $file = $image->getImageBlob();

        Storage::disk($this->disk)->put($filePath, $file);
    }

    public function productMetaValues()
    {
        return $this->belongsToMany(
            'App\Models\ProductMetaValue',
            'product_product_meta_value',
            'product_id',
            'pmv_id'
        );
    }

    public function productUnits()
    {
        return $this->hasMany('App\Models\ProductUnit');
    }

    public function companies()
    {
        return $this->belongsToMany(
            'App\Models\Company',
            'company_product',
            'product_id',
            'company_id'
        );
    }

    public function companyProducts()
    {
        return $this->hasMany('App\Models\CompanyProduct');
    }

    public function expeditionProducts()
    {
        return $this->hasMany('App\Models\ExpeditionProduct');
    }

    /**
     * The units that belong to the product.
     */
    public function units()
    {
        return $this->belongsToMany(
            'App\Models\Unit',
            'product_unit',
            'product_id',
            'unit_id'
        );
    }

    public function scopeStockAtMinimumOrLess($q)
	{
		$q->where('stock', '<=', DB::raw('minimum_stock'));
	}

    public function scopeOutOfStock($q)
	{
		$q->where('stock', '<=', DB::raw('0'));
	}

    public function scopeInCompany($q, $companyId)
    {
        $q->whereHas('companies', function ($q) use ($companyId) {
            $q->where('companies.id', $companyId);
        });
    }

    public function scopeInCompanies($q, array $companyIds)
    {
        return $q->whereHas('companies', function ($q) use ($companyIds) {
            $q->whereIn('companies.id', $companyIds);
        });
    }

    /**
     * @return null|string path to the thumbnail file based on the image attr
     */
    public function getThumbnailImageAttribute()
    {
        $info = array_filter(pathinfo($this->image));

        if (!empty($info)) {
            return $info['dirname'].'/'.$info['filename'].'_thumb.'.$info['extension'];
        }
    }

    public function scopeCodeLike($q, $term)
    {
        $q->where('code', 'like', '%'.$term.'%');
    }

    public function scopeNameLike($q, $term)
    {
        $q->where('name', 'like', '%'.$term.'%');
    }

    public function scopeSkuLike($q, $term)
    {
        $q->where('sku', 'like', '%'.$term.'%');
    }

    public function scopeBasePriceFilter($q, $term)
    {
        $p = Functions::translateNumericFilterTerm($term);
        $q->where('base_price', $p['operator'], $p['term']);
    }

    public function scopePriceFilter($q, $term)
    {
        $p = Functions::translateNumericFilterTerm($term);
        $q->where('price', $p['operator'], $p['term']);
    }

    public function scopeStockFilter($q, $term)
    {
        $p = Functions::translateNumericFilterTerm($term);
        $q->where('stock', $p['operator'], $p['term']);
    }
}
