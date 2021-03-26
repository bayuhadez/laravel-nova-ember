import EmberRouter from '@ember/routing/router';
import config from 'rcf/config/environment';
import ENV from 'rcf/config/environment';

export default class Router extends EmberRouter {
  location = config.locationType;
  rootURL = config.rootURL;
}

Router.map(function() {
  if (ENV.APP.tenant == 'reactivecodes') {
      this.route('home-saas', {path: '/'});
  } else if (ENV.APP.tenant == 'xxx') {
      this.route('xxx', {path: '/'}, function () {
          this.route('dashboard', {path: '/'});
          this.route('checkout', {path: '/checkout/:id'});
          this.route('forgot-password');
          this.route('login');
          this.route('logout');
          this.route('manage-seminar');
          this.route('mentor-request');
          this.route('mentor-stream', {path: '/mentor-stream/:key'});
          this.route('my-products');
          this.route('product-detail', {path: '/product-detail/:id'});
          this.route('register');
          this.route('reset-password', { path: '/reset-password/:token' });
          this.route('shopping-cart', {path: '/shopping-cart'});
          this.route('stream', {path: '/stream/:key'});
          this.route('user');
          this.route('landing', function() {
              this.route('after-registration', {path: '/after-registration/:email'});
              this.route('verified-email', {path: '/verified-email'});
          });
          this.route('orders', function () {
              this.route('index', { path: '/' });
              this.route('show', {path: '/:order_id'});
          });
          this.route('products', function(){
              this.route('index', { path: '/' });
              this.route('show', { path: '/:product_id' });
          });
          this.route('profile', function() {
              this.route('reset-password');
              this.route('edit', { path: '/:user_id'});
          });
          this.route('faq');
      });
  } else if (ENV.APP.tenant == 'axx') {
      this.route('axx', {path: '/'}, function () {
        this.route('dashboard', {path: '/'});
        this.route('login');

        this.route('master', function() {
          this.route('services', function() {
            this.route('index', { path: '/' });
            this.route('edit', { path: '/:service_id/edit' });
            this.route('add');
          });

          this.route('service-category', function() {
            this.route('create');
            this.route('edit',{ path: '/:id/edit'});
          });

          this.route('companies', function() {
            this.route('index', { path: '/' });
            this.route('edit', { path: '/:company_id/edit' });
            this.route('add');
          });

          this.route('warehouses', function() {
            this.route('index', { path: '/' });
            this.route('edit', { path: '/:warehouse_id/edit' });
            this.route('add');
          });

          this.route('stock-divisions', function() {
            this.route('index', { path: '/' });
            this.route('edit', { path: '/:company_id/edit' });
          });

          this.route('products', function () {
            this.route('index', { path: '/' });
            this.route('edit', { path: '/:product_id/edit' }, function () {
              this.route('product-stock', {path: '/product-stock/:stock_division_id'});
              this.route('stock-in-rack', {path: '/stock-in-rack/:stock_division_id'});
            });
            this.route('add');
          });

          this.route('customers', function () {
            this.route('index', { path: '/' });
            this.route('edit', { path: '/:customer_id/edit' });
            this.route('add');
          });

          this.route('suppliers', function () {
            this.route('index', { path: '/' });
            this.route('add');
            this.route('edit', { path: '/:supplier_id/edit' });
          });

          this.route('product-meta-fields', function () {
            this.route('index', { path: '/' });
            this.route('edit', { path: '/:product_meta_field_id/edit' });
            this.route('add');
          });
          this.route('staffs', function () {
            this.route('index', { path: '/' });
            this.route('edit', { path: '/:staff_id/edit' });
            this.route('add');
          });
          this.route('staff-categories');
          this.route('staff-positions');
          this.route('supplier-categories');
          this.route('expeditions', function() {
            this.route('index', { path: '/' });
            this.route('add');
            this.route('edit', { path: '/:expedition_id/edit' });
          });
          this.route('expedition-categories');
          this.route('service-categories');
          this.route('payment-methods', function() {
            this.route('index', { path: '/' });
          });
          this.route('wallets', function() {
            this.route('index', { path: '/' });
          });
          this.route('users', function () {
            this.route('index', { path: '/' });
            this.route('add');
            this.route('edit', { path: '/:user_id/edit' });
          });
          this.route('roles', function() {
            this.route('index', { path: '/' });
          });
          this.route('product-category');
          this.route('product-categories', function() {});
        });

        this.route('masters', function() {
          this.route('product-category');
        });

        this.route('sales-orders', function() {
          this.route('index', { path: '/' });
          this.route('add');
          this.route('edit', {path: '/:sales_order_id/edit'});
        });

        this.route('sales-receipts', function() {
          this.route('index', { path: '/' });
          this.route('add');
          this.route('edit', {path: '/:sales_receipt_id/edit'});
        });

        this.route('purchases', function() {
          this.route('request-orders', function () {
            this.route('index', { path: '/' });
            this.route('add');
            this.route('edit', { path: '/:request_order_id/edit' });
          });
          this.route('pre-orders', function() {
            this.route('add');
            this.route('edit', { path: '/:pre_order_id/edit'});
          });
          this.route('purchase-receipts', function() {
            this.route('add');
            this.route('edit', { path: '/:purchase_receipt_id/edit'});
          });
        });

        this.route('retailsellers', function() {
          this.route('rswo', function() {
            this.route('index', { path: '/' });
          });
        });
        this.route('prepare-or-return-product', function() {});
      });
  }
  // route for 404
  this.route('not-found', { path: '/*path' });
});
