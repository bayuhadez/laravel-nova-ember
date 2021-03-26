<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use CloudCreativity\LaravelJsonApi\LaravelJsonApi;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
	public function boot()
	{
        LaravelJsonApi::defaultApi('v1');
	    Schema::defaultStringLength(191);

        // Model Observers
        \App\Models\Banner::observe(\App\Observers\BannerObserver::class);
        \App\Models\Company::observe(\App\Observers\CompanyObserver::class);
        \App\Models\CompanyUserRole::observe(\App\Observers\CompanyUserRoleObserver::class);
        \App\Models\CompanyWarehouse::observe(\App\Observers\CompanyWarehouseObserver::class);
        \App\Models\Customer::observe(\App\Observers\CustomerObserver::class);
        \App\Models\Expedition::observe(\App\Observers\ExpeditionObserver::class);
        \App\Models\Faq::observe(\App\Observers\FaqObserver::class);
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
        \App\Models\PreOrder::observe(\App\Observers\PreOrderObserver::class);
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);
        \App\Models\ProductCategory::observe(\App\Observers\ProductCategoryObserver::class);
        \App\Models\ProductSalesOrder::observe(\App\Observers\ProductSalesOrderObserver::class);
        \App\Models\ProductStockMovement::observe(\App\Observers\ProductStockMovementObserver::class);
        \App\Models\ProductTransactionReceipt::observe(\App\Observers\ProductTransactionReceiptObserver::class);
        \App\Models\PurchaseReceipt::observe(\App\Observers\PurchaseReceiptObserver::class);
        \App\Models\Rack::observe(\App\Observers\RackObserver::class);
        \App\Models\RequestOrder::observe(\App\Observers\RequestOrderObserver::class);
        \App\Models\RequestOrderProduct::observe(\App\Observers\RequestOrderProductObserver::class);
        \App\Models\SalesOrder::observe(\App\Observers\SalesOrderObserver::class);
        \App\Models\SalesReceipt::observe(\App\Observers\SalesReceiptObserver::class);
        \App\Models\SeminarProductMeta::observe(\App\Observers\SeminarProductMetaObserver::class);
        \App\Models\Service::observe(\App\Observers\ServiceObserver::class);
        \App\Models\Staff::observe(\App\Observers\StaffObserver::class);
        \App\Models\Supplier::observe(\App\Observers\SupplierObserver::class);
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\Voucher::observe(\App\Observers\VoucherObserver::class);
        \App\Models\Warehouse::observe(\App\Observers\WarehouseObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
