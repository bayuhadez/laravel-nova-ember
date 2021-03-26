<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Resolver
    |--------------------------------------------------------------------------
    |
    | The API's resolver is the class that works out the fully qualified
    | class name of adapters, schemas, authorizers and validators for your
    | resource types. We recommend using our default implementation but you
    | can override it here if desired.
     */
    'resolver' => \CloudCreativity\LaravelJsonApi\Resolver\ResolverFactory::class,

    /*
    |--------------------------------------------------------------------------
    | Root Namespace
    |--------------------------------------------------------------------------
    |
    | The root namespace for JSON API classes for this API. If `null`, the
    | namespace will default to `JsonApi` within your application's root
    | namespace (obtained via Laravel's `Application::getNamespace()`
    | method).
    |
    | The `by-resource` setting determines how your units are organised within
    | your root namespace.
    |
    | - true:
    |   - e.g. App\JsonApi\Posts\{Adapter, Schema, Validators}
    |   - e.g. App\JsonApi\Comments\{Adapter, Schema, Validators}
    | - false:
    |   - e.g. App\JsonApi\Adapters\PostAdapter, CommentAdapter}
    |   - e.g. App\JsonApi\Schemas\{PostSchema, CommentSchema}
    |   - e.g. App\JsonApi\Validators\{PostValidator, CommentValidator}
    |
     */
    'namespace' => null,
    'by-resource' => false,

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    |
    | Here you map the list of JSON API resources in your API to the actual
    | record (model/entity) classes they relate to.
    |
    | For example, if you had a `posts` JSON API resource, that related to
    | an Eloquent model `App\Post`, your mapping would be:
    |
    | `'posts' => App\Post::class`
     */
    'resources' => [
        'addresses' => App\Models\Address::class,
        'banners' => App\Models\Banner::class,
        'chat-rooms' => App\Models\ChatRoom::class,
        'chats' => App\Models\Chat::class,
        'companies' => App\Models\Company::class,
        'company-addresses' => App\Models\CompanyAddress::class,
        'company-warehouses' => App\Models\CompanyWarehouse::class,
        'company-users' => App\Models\CompanyUser::class,
        'countries' => App\Models\Country::class,
        'faqs' => App\Models\Faq::class,
        'licenses' => App\Models\License::class,
        'order-details' => App\Models\OrderDetail::class,
        'orders' => App\Models\Order::class,
        'people' => App\Models\Person::class,
        'person-addresses' => App\Models\PersonAddress::class,
        'phones' => App\Models\Phone::class,
        'product-banners' => App\Models\ProductBanner::class,
        'product-categories' => App\Models\ProductCategory::class,
        'products' => App\Models\Product::class,
        'provinces' => App\Models\Province::class,
        'purchase-receipts' => App\Models\PurchaseReceipt::class,
        'sales-receipts' => App\Models\SalesReceipt::class,
        'racks' => App\Models\Rack::class,
        'regencies' => App\Models\Regency::class,
        'roles' => App\Models\Role::class,
        'seminar-product-metas' => App\Models\SeminarProductMeta::class,
        'seminar-product-sponsors' => App\Models\SeminarProductSponsor::class,
        'sponsors' => App\Models\Sponsor::class,
        'transactions' => App\Models\MidtransTransaction::class,
        'users' => App\Models\User::class,
        'vouchers' => App\Models\Voucher::class,

        'company-customers' => App\Models\CompanyCustomer::class,
        'company-products' => App\Models\CompanyProduct::class,
        'company-services' => App\Models\CompanyService::class,
        'currencies' => App\Models\Currency::class,
        'customers' => App\Models\Customer::class,
        'expedition-categories' => App\Models\ExpeditionCategory::class,
        'expedition-products' => App\Models\ExpeditionProduct::class,
        'expeditions' => App\Models\Expedition::class,
        'permissions' => App\Models\Permission::class,
        'pre-order-products' => App\Models\PreOrderProduct::class,
        'pre-orders' => App\Models\PreOrder::class,
        'product-stocks' => App\Models\ProductStock::class,
        'product-stock-movements' => App\Models\ProductStockMovement::class,
        'product-brands' => App\Models\ProductBrand::class,
        'product-meta-field-groups' => App\Models\ProductMetaFieldGroup::class,
        'product-meta-fields' => App\Models\ProductMetaField::class,
        'product-meta-values' => App\Models\ProductMetaValue::class,
        'product-sales-orders' => App\Models\ProductSalesOrder::class,
        'product-sales-receipts' => App\Models\ProductSalesReceipt::class,
        'product-transaction-receipts' => App\Models\ProductTransactionReceipt::class,
        'product-units' => App\Models\ProductUnit::class,
        'request-order-products' => App\Models\RequestOrderProduct::class,
        'request-orders' => App\Models\RequestOrder::class,
        'sales-order-services' => App\Models\SalesOrderService::class,
        'sales-orders' => App\Models\SalesOrder::class,
        'sales-receipt-services' => App\Models\SalesReceiptService::class,
        'service-transaction-receipts' => App\Models\ServiceTransactionReceipt::class,
        'service-categories' => App\Models\ServiceCategory::class,
        'services' => App\Models\Service::class,
        'staff-categories' => App\Models\StaffCategory::class,
        'staff-positions' => App\Models\StaffPosition::class,
        'staffs' => App\Models\Staff::class,
        'stock-divisions' => App\Models\StockDivision::class,
        'supplier-categories' => App\Models\SupplierCategory::class,
        'suppliers' => App\Models\Supplier::class,
        'transaction-receipts' => App\Models\TransactionReceipt::class,
        'units' => App\Models\Unit::class,
        'warehouse-categories' => App\Models\WarehouseCategory::class,
        'warehouses' => App\Models\Warehouse::class,
        'payment-methods' => App\Models\PaymentMethod::class,
        'wallets' => App\Models\Wallet::class,

        // Non Eloquent
        'registrars' => App\JsonApi\Records\Registrar::class,
        'stock-division-by-company-divisions' => App\JsonApi\Records\StockDivisionByCompanyDivision::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Eloquent
    |--------------------------------------------------------------------------
    |
    | Whether your JSON API resources predominantly relate to Eloquent models.
    | This is used by the package's generators.
    |
    | You can override the setting here when running a generator. If the
    | setting here is `true` running a generator with `--no-eloquent` will
    | override it; if the setting is `false`, then `--eloquent` is the override.
    |
     */
    'use-eloquent' => true,

    /*
    |--------------------------------------------------------------------------
    | URL
    |--------------------------------------------------------------------------
    |
    | The API's url, made up of a host, URL namespace and route name prefix.
    |
    | If a JSON API is handling an inbound request, the host will always be
    | detected from the inbound HTTP request. In other circumstances
    | (e.g. broadcasting), the host will be taken from the setting here.
    | If it is `null`, the `app.url` config setting is used as the default.
    | If you set `host` to `false`, the host will never be appended to URLs
    | for inbound requests.
    |
    | The name setting is the prefix for route names within this API.
    |
     */
    'url' => [
        'host' => null,
        'namespace' => '/api/v1',
        'name' => 'api:v1:',
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported JSON API Extensions
    |--------------------------------------------------------------------------
    |
    | Refer to the JSON API spec for information on supported extensions.
    |
     */
    'supported-ext' => null,

    /*
    |--------------------------------------------------------------------------
    | Encoding Media Types
    |--------------------------------------------------------------------------
    |
    | This defines the JSON API encoding used for particular media
    | types supported by your API. This array can contain either
    | media types as values, or can be keyed by a media type with the value
    | being the options that are passed to the `json_encode` method.
    |
    | These values are also used for Content Negotiation. If a client requests
    | via the HTTP Accept header a media type that is not listed here,
    | a 406 Not Acceptable response will be sent.
    |
    | If you want to support media types that do not return responses with JSON
    | API encoded data, you can do this at runtime. Refer to the
    | Content Negotiation chapter in the docs for details.
    |
     */
    'encoding' => [
        'application/vnd.api+json' => JSON_PRESERVE_ZERO_FRACTION,
        'text/plain' => JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION,
    ],

    /*
    |--------------------------------------------------------------------------
    | Decoding Media Types
    |--------------------------------------------------------------------------
    |
    | This defines the media types that your API can receive from clients.
    | This array is keyed by expected media types, with the value being the
    | service binding that decodes the media type.
    |
    | These values are also used for Content Negotiation. If a client sends
    | a content type not listed here, it will receive a
    | 415 Unsupported Media Type response.
    |
    | Decoders can also be calculated at runtime, and/or you can add support
    | for media types for specific resources or requests. Refer to the
    | Content Negotiation chapter in the docs for details.
    |
     */
    'decoding' => [
        'application/vnd.api+json',
    ],

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    | Providers allow vendor packages to include resources in your API. E.g.
    | a Shopping Cart vendor package might define the `orders` and `payments`
    | JSON API resources.
    |
    | A package author will define a provider class in their package that you
    | can add here. E.g. for our shopping cart example, the provider could be
    | `Vendor\ShoppingCart\JsonApi\ResourceProvider`.
    |
     */
    'providers' => [],

];
