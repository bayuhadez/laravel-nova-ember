<?php

use Illuminate\Http\Request;
use CloudCreativity\LaravelJsonApi\Facades\JsonApi;
//use CloudCreativity\LaravelJsonApi\Routing\RouteRegistrar;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
Route::namespace('Api')->prefix('api')->group(function () {

    Route::namespace('V1')->prefix('v1')->group(function () {
        Route::post('login', 'AuthController@login');

        Route::namespace('Auth')->prefix('auth')->group(function () {
            // TODO https://laravel.com/docs/6.x/upgrade#email-verification-route-change
            Route::get('email/verify/{cid}/{cem}', 'VerificationApiController@verify')->name('verificationapi.verify');
            Route::post('password/request', 'ResetPasswordController@sendPasswordResetLink')->name('password.request');
            Route::post('password/reset', 'ResetPasswordController@callResetPassword')->name('password.reset');
        });

        Route::post('check-rack-code-unique', 'ValidatorController@checkRackCodeUnique');
        Route::post('check-rack-name-unique-in-warehouse', 'ValidatorController@checkRackNameUniqueInWarehouse');
    });

    Route::middleware('auth:api')->group(function () {
        Route::namespace('V1')->prefix('v1')->group(function () {
            Route::get('/user', 'AuthController@user');
            Route::get('logout', 'AuthController@logout');
            Route::get('products/{productId}/is-mine', 'ProductController@getIsMine');
            Route::get('products/{productId}/is-purchased', 'ProductController@getIsPurchased');
            Route::get('products/low-stock-count', 'ProductController@getLowStockCount');
            Route::get(
                'products/{productId}/get-product-stock-in-rack-by-stock-division/{stockDivisionId}',
                'ProductController@getProductStockInRackByStockDivision'
            );
            Route::get('products/out-of-stock-count', 'ProductController@getOutOfStockCount');
            Route::get('product-stock/{productId}/get-in-product-by-stock-division', 'ProductStockController@getInProductByStockDivision');
            Route::post('mentor-request', 'MentorRequestController@storeMentorRequest');
            Route::post('profile/update-profile', 'AuthController@updateUserProfile');
            Route::post('profile/password/reset', 'AuthController@profileResetPassword');
            Route::post('signed-url-to-manage-route', 'AutoLoginToNovaController@generateSignedRouteToResourceProducts');

            Route::middleware('verified')->group(function () {
                Route::post('orders/create-with-order-details', 'OrdersController@createWithOrderDetails');
                Route::post('orders/get-token-payment', 'OrdersController@getTokenPayment');
            });

            Route::namespace('Auth')->prefix('auth')->group(function () {
                // TODO https://laravel.com/docs/6.x/upgrade#email-verification-route
                Route::get('email/resend', 'VerificationApiController@resend')->name('verificationapi.resend');
            });
        });
    });

});

//Route::middleware('auth:api')->group(function () {

JsonApi::register('v1')->withNamespace('Api')->middleware('auth:api')->routes(function ($api, $router) {

    $api->resource('addresses')->relationships(function ($r) {
        $r->hasMany('company-addresses');
        $r->hasMany('person-addresses');
        $r->hasOne('country');
        $r->hasOne('province');
        $r->hasOne('regency');
    });

    $api->resource('companies')->relationships(function ($relations) {
        $relations->hasOne('created-by');
        $relations->hasOne('updated-by');
        $relations->hasOne('parent-company');
        $relations->hasMany('addresses');
        $relations->hasMany('customers');
        $relations->hasMany('company-addresses');
        $relations->hasMany('company-warehouses');
        $relations->hasMany('company-customers');
        $relations->hasMany('products');
        $relations->hasMany('services');
        $relations->hasMany('product-categories');
        $relations->hasMany('children-company');
        $relations->hasMany('warehouses');
        $relations->hasMany('staffs');
        $relations->hasMany('stock-divisions');
    });

    $api->resource('company-addresses')->relationships(function ($r) {
        $r->hasOne('address');
        $r->hasOne('company');
    });

    $api->resource('company-warehouses')->relationships(function ($relations) {
        $relations->hasOne('company');
        $relations->hasOne('warehouse');
    });

    $api->resource('product-categories', [
        'has-one' => 'company',
        'has-many' => 'products'
    ]);

    $api->resource('roles')->relationships(function ($r) {
        $r->hasMany('users');
        $r->hasMany('permissions');
    });
    
    $api->resource('expedition-products')->relationships(function ($r) {
        $r->hasOne('expedition');
        $r->hasOne('product');
    });

    $api->resource('company-products', [
        'has-one' => [
            'company',
            'product'
        ],
    ]);

    $api->resource('company-services')->relationships(function ($relations) {
        $relations->hasOne('company');
        $relations->hasOne('service');
    });

    // product
    $api->resource('units');

    $api->resource('product-units')
        ->relationships(function ($relations) {
            $relations->hasOne('product');
            $relations->hasOne('unit');
            $relations->hasOne('converted-unit');
        });

    $api->resource('products')
        ->relationships(function ($relations) {
            $relations->hasOne('productCategory');
            $relations->hasOne('company');
            $relations->hasOne('user');
            $relations->hasOne('seminar-product-meta');
            $relations->hasMany('product-meta-values');
            $relations->hasMany('chat-rooms');
            $relations->hasMany('product-units');
            $relations->hasMany('units');
            $relations->hasMany('company-products');
            $relations->hasMany('expedition-products');
        })
        ->authorizer('product');

    $api->resource('order-details', [
        'has-one' => ['order', 'product'],
        'only' => ['read']
    ]);

    //$api->resource('licenses');
    $api->resource('chats');

    $api->resource('chat-rooms', [
        'has-many' => ['chats']
    ]);

    $api->resource('countries')->relationships(function ($r) {
        $r->hasMany('addresses');
    })->readonly();

    $api->resource('customers')->relationships(function ($relations) {
        $relations->hasOne('company');
        $relations->hasOne('parent-customer');
        $relations->hasOne('children-customer');
        $relations->hasOne('person');
        $relations->hasOne('pic');
        $relations->hasMany('company-customers');
        $relations->hasMany('sales-orders');
    });

    $api->resource('sales-orders')->relationships(function ($relations) {
        $relations->hasOne('customer');
        $relations->hasOne('company');
        $relations->hasOne('user');
        $relations->hasOne('sales');
        $relations->hasOne('delivery-recipient-customer');
        $relations->hasOne('delivery-address');
        $relations->hasOne('warehouse-staff');
        $relations->hasMany('product-sales-orders');
        $relations->hasMany('sales-order-services');
    });

    $api->resource('product-sales-orders')->relationships(function ($relations) {
        $relations->hasOne('sales-order');
        $relations->hasOne('stock-division');
        $relations->hasOne('product');
        $relations->hasOne('product-transaction-receipt');
        $relations->hasMany('product-stock-movements');
    });

    $api->resource('product-sales-receipts')->relationships(function ($relations) {
        $relations->hasOne('product');
        $relations->hasOne('product-sales-order');
        $relations->hasOne('sales-receipt');
    });

    $api->resource('product-stocks')->relationships(function ($relations) {
        $relations->hasOne('rack');
        $relations->hasOne('stock-division');
        $relations->hasOne('product');
        $relations->hasMany('product-stock-movements');
    });

    $api->resource('product-stock-movements')->relationships(function ($relations) {
        $relations->hasOne('customer');
        $relations->hasOne('rack');
        $relations->hasOne('stock-division');
        $relations->hasOne('product');
        $relations->hasOne('purchase-receipt');
        $relations->hasOne('sales-receipt');
        $relations->hasOne('product-sales-order');
        $relations->hasOne('product-stock');
    });

    $api->resource('sales-order-services')->relationships(function ($relations) {
        $relations->hasOne('sales-order');
        $relations->hasOne('service');
        $relations->hasOne('service-transaction-receipt');
    });

    $api->resource('sales-receipt-services')->relationships(function ($relations) {
        $relations->hasOne('sales-receipt');
        $relations->hasOne('sales-order-service');
        $relations->hasOne('service');
    });

    $api->resource('service-transaction-receipts')->relationships(function ($relations) {
        $relations->hasOne('transaction-receipt');
        $relations->hasOne('sales-order-service');
        $relations->hasOne('service');
    });

    $api->resource('service-categories')->relationships(function ($relations) {
        $relations->hasOne('service');
    });

    $api->resource('services')->relationships(function ($relations) {
        $relations->hasOne('service-category');
        $relations->hasMany('company-services');
    });

    $api->resource('currencies')->readOnly();

    $api->resource('phones')->relationships(function ($relations) {
        $relations->hasOne('country');
    });

    $api->resource('expedition-categories');

    $api->resource('expeditions')->relationships(function ($relations) {
        $relations->hasMany('expedition-products');
        $relations->hasOne('country');
        $relations->hasOne('currency');
        $relations->hasOne('expedition-category');
        $relations->hasOne('province');
        $relations->hasOne('regency');
    });
    
    $api->resource('racks')->relationships(function ($relations) {
        $relations->hasOne('warehouse');
        $relations->hasMany('product-stocks');
    });

    $api->resource('staffs')->relationships(function ($relations) {
        $relations->hasOne('person');
        $relations->hasOne('company');
        $relations->hasMany('staff-categories');
        $relations->hasMany('staff-positions');
        $relations->hasMany('company-customers');
    });

    $api->resource('staff-categories')->relationships(function ($relations) {
        $relations->hasMany('staffs');
    });

    $api->resource('staff-positions')->relationships(function ($relations) {
        $relations->hasMany('staffs');
    });

    $api->resource('stock-divisions')->relationships(function ($relations) {
        $relations->hasOne('company');
    });

    $api->resource('supplier-categories')->relationships(function ($relations) {
        $relations->hasOne('supplier');
    });

    $api->resource('suppliers')->relationships(function ($relations) {
        $relations->hasOne('company');
        $relations->hasOne('currency');
        $relations->hasOne('pic');
        $relations->hasOne('supplier-category');
        $relations->hasOne('user');
    });
    
    $api->resource('person-addresses')->relationships(function ($r) {
        $r->hasOne('address');
        $r->hasOne('person');
    });

    $api->resource('product-brands');

    $api->resource('provinces')->relationships(function ($r) {
        $r->hasMany('addresses');
        $r->hasMany('regencies');
    })->readonly();

    $api->resource('regencies')->relationships(function ($r) {
        $r->hasOne('province');
        $r->hasMany('addresses');
    })->readonly();

    $api->resource('product-brands');

    $api->resource('product-meta-fields')->relationships(function ($relations) {
        $relations->hasOne('product-meta-field-group');
        $relations->hasMany('product-meta-values');
    });

    $api->resource('product-meta-field-groups')->relationships(function ($r) {
        $r->hasMany('product-meta-fields');
    });

    $api->resource('product-meta-values');

    $api->resource('warehouses')->relationships(function ($relations) {
        $relations->hasMany('companies');
        $relations->hasMany('company-warehouses');
        $relations->hasMany('warehouse-categories');
        $relations->hasMany('racks');
    });

    $api->resource('warehouse-categories')->relationships(function ($relations) {
        $relations->hasMany('warehouses');
    });

    $api->resource('company-customers')->relationships(function ($relations) {
        $relations->hasOne('company');
        $relations->hasOne('customer');
        $relations->hasMany('staffs');
    });

    $api->resource('request-orders')->relationships(function ($relations) {
        $relations->hasOne('company');
        $relations->hasOne('created-by');
        $relations->hasOne('pre-order');
        $relations->hasOne('staff');
        $relations->hasOne('staff-position');
        $relations->hasMany('request-order-products');
    });

    $api->resource('request-order-products')->relationships(function ($relations) {
        $relations->hasOne('product');
        $relations->hasOne('product-unit');
        $relations->hasOne('request-order');
        $relations->hasOne('unit');
    });

    $api->resource('pre-orders')->relationships(function ($relations) {
        $relations->hasMany('pre-order-products');
        $relations->hasMany('request-orders');
        $relations->hasOne('company');
        $relations->hasOne('created-by');
        $relations->hasOne('currency');
        $relations->hasOne('supplier');
    });

    $api->resource('pre-order-products')->relationships(function ($relations) {
        $relations->hasOne('pre-order');
        $relations->hasOne('product');
        $relations->hasOne('product-unit');
    });

    $api->resource('sales-receipts')->relationships(function ($relations) {
        $relations->hasOne('company');
        $relations->hasOne('created-by');
        $relations->hasOne('address');
        $relations->hasMany('product-sales-receipts');
        $relations->hasMany('sales-receipt-services');
    });

    $api->resource('transaction-receipts')->relationships(function ($relations) {
        $relations->hasOne('customer');
        $relations->hasOne('supplier');
        $relations->hasMany('product-transaction-receipts');
        $relations->hasMany('service-transaction-receipts');
    });

    $api->resource('product-transaction-receipts')->relationships(function ($relations) {
        $relations->hasOne('pre-order-product');
        $relations->hasOne('product-sales-order');
        $relations->hasOne('product');
        $relations->hasOne('product-unit');
        $relations->hasMany('product-stock-movements');
    });

    $api->resource('payment-methods');

    $api->resource('wallets')->relationships(function ($relations) {
        $relations->hasMany('payment-methods');
        $relations->hasMany('companies');
    });

});

// Custom Controller
JsonApi::register('v1')->withNamespace('Api\V1')->middleware('auth:api')->routes(function ($api, $router) {
    $api->resource('orders', [
        'has-many' => 'order-details',
        'has-one' => ['company', 'user', 'transaction', 'voucher'],
        'only' => ['index', 'read'],
    ])->controller();

    $api->resource('users', [
        'only' => ['create', 'update', 'delete']
    ])
        ->relationships(function ($relations) {
            $relations->hasOne('person');
            $relations->hasMany('roles');
            $relations->hasMany('companies')->readOnly();
        })
        ->controller()
        ->routes(function ($users) {
            $users->get('current', 'current');
        });

    $api->resource('purchase-receipts')
        ->relationships(function ($relations) {
            $relations->hasOne('company');
            $relations->hasOne('created-by');
            $relations->hasOne('currency');
            $relations->hasOne('transaction-receipt');
            $relations->hasMany('pre-orders');
        })
        ->controller()
        ->routes(function ($purchaseReceipts) {
            $purchaseReceipts->post('{record}/update-calculation', 'updateCalculation');
        });
});

JsonApi::register('v1')->withNamespace('Api\V1')->routes(function ($api, $router) {

    $api->resource('people')->relationships(function ($r) {
        $r->hasMany('person-addresses');
        $r->hasOne('user');
        $r->hasOne('staff');
        $r->hasOne('regency');
    });

    $api->resource('banners', [
        'has-one' => ['company'],
        'only' => ['index', 'read']
    ]);
    $api->resource('products', [
        'has-one' => ['company', 'user', 'product-banner', 'seminar-product-meta'],
        'has-many' => ['product-categories'],
        'only' => ['index', 'read']
    ]);

    $api->resource('registrars', [
        'only' => ['create']
    ])->controller();

    $api->resource('users', [
        'has-one' => 'person',
        'has-many' => ['company-users'],
        'only' => ['index', 'read']
    ]);

    $api->resource('seminar-product-metas', [
        'has-many' => 'seminar-product-sponsors',
        'has-one' => ['product', 'speaker'],
        'only' => ['index', 'read']
    ]);

    $api->resource('seminar-product-sponsors', [
        'has-one' => 'seminar-product-meta',
        'only' => ['index', 'read']
    ]);

    $api->resource('sponsors', [
        'only' => ['index', 'read']
    ]);

    $api->resource('product-banners', [
        'has-one' => 'product',
        'only' => ['index', 'read']
    ]);

    $api->resource('faqs', [
        'only' => ['index', 'read']
    ]);

    $api->resource('vouchers', [
        'has-one' => ['company'],
        'has-many' => ['orders']
    ]);

    $api->resource('stock-division-by-company-divisions');

    $api->resource('company-users', [
        'has-many' => ['stock-divisions', 'roles'],
        'has-one' => ['company', 'user'],
    ]);

    $api->resource('permissions');
});
/*
Route::namespace('Api')->prefix('api')->group(function () {

    Route::middleware('auth:api')->group(function () {
        Route::namespace('V2')->prefix('v2')->group(function () {
            Route::post('orders/create', 'OrderController@create');
        });
    });

});
*/


