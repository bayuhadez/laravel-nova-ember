<?php

namespace App\JsonApi\Adapters;

use App\Models\Company;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CompanyAdapter extends BaseAdapter
{
    
    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [
        'childrenOfCompany',
        'childInCompany',
        'notUsedByService',
        'meAndChildren',
        'notUsedByProduct',
        'notUsedByUser',
        'notUsedByStaffPerson',
    ];

    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

        /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new Company(), $paging);
    }
    
    // Relationships [
    public function customers()
    {
        return $this->hasMany();
    }

    public function owner()
    {
		return $this->belongsToMany();
    }

    public function products()
    {
        return $this->hasMany();
    }

    public function services()
    {
        return $this->hasMany();
    }

    public function productCategories()
    {
      return $this->hasMany();
    }

    public function banners()
    {
		return $this->hasMany();
    }

    public function warehouses()
    {
		return $this->hasMany();
    }
    
    public function companyAddresses()
    {
		return $this->hasMany();
    }

    public function companyWarehouses()
    {
		return $this->hasMany();
    }

    public function companyCustomers()
    {
		return $this->hasMany();
    }

    public function parentCompany()
    {
        return $this->belongsTo();
    }

    public function childrenCompany()
    {
        return $this->hasMany();
    }

    public function createdBy()
    {
        return $this->belongsTo();
    }

    public function updatedBy()
    {
        return $this->belongsTo();
    }

    public function staffs()
    {
		return $this->hasMany();
    }

    public function stockDivisions()
    {
		return $this->hasMany();
    }
    // ]

    /**
     * @param Builder $query
     * @param Collection $filters
     * @return void
     */
    protected function filter($query, Collection $filters)
    {
        $this->filterWithScopes($query, $filters);

		if ($filters->has('name')) {
			$query->where('companies.name', 'like', '%' . $filters->get('name') . '%');
		}
    }

}
