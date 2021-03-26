<?php

namespace App\JsonApi\Adapters;

use App\Models\User;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class UserAdapter extends BaseAdapter
{

    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    protected $guarded = ['password'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
    ];

    protected $includePaths = [
		'person' => null,
    ];

    protected $relationships = [
        'person',
        'roles',
        'companies',
        'company-users',
    ];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new User(), $paging);
    }

    public function person()
    {
        return $this->hasOne();
    }

    public function roles()
    {
        return $this->hasMany();
    }

    public function companies()
    {
        return $this->hasMany();
    }

    public function companyUsers()
    {
        return $this->hasMany();
    }

    /**
     * @param Builder $query
     * @param Collection $filters
     * @return void
     */
    protected function filter($query, Collection $filters)
    {
        // TODO
        if ($filters->has('username')) {
			$query->where('users.email', 'like', $filters->get('username'));
		}
    }

    /**
     * @inheritDoc
     * updating hook
     */
    protected function updating(User $user): void
    {
        if (!empty($user->id)) {
            if (empty($user->password)) {
                unset($user->password);
            }
        }
    }

}
