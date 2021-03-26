<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\License;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\User;
use App\Models\SeminarProductMeta;
use App\Models\Sponsor;
use App\Models\Banner;
use App\Policies\CompanyPolicy;
use App\Policies\LicensePolicy;
use App\Policies\OrderPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\ProductCategoryPolicy;
use App\Policies\ProductPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Policies\SeminarProductMetaPolicy;
use App\Policies\SponsorPolicy;
use App\Policies\BannerPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
		// 'App\Model' => 'App\Policies\ModelPolicy',
		Company::class => CompanyPolicy::class,
		License::class => LicensePolicy::class,
		Order::class => OrderPolicy::class,
		Payment::class => PaymentPolicy::class,
		Permission::class => PermissionPolicy::class,
		Product::class => ProductPolicy::class,
		ProductCategory::class => ProductCategoryPolicy::class,
		Role::class => RolePolicy::class,
		User::class => UserPolicy::class,
		SeminarProductMeta::class => SeminarProductMetaPolicy::class,
		Sponsor::class => SponsorPolicy::class,
		Banner::class => BannerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

		Passport::routes();
    }
}
