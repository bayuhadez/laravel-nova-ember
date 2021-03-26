import Component from '@ember/component';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default Component.extend({
    intl: service(),

    navs: computed('session.isAuthenticated', 'currentUser.user.id', function () {

        /**
         * placeholder for nav items. define them in the following format
         * [
         *     {
         *
         *         // nav item's label. usually need to be translated
         *         label: this.intl.t('label'),
         *
         *         // target route of the nav item.
         *         // tenant's prefix will be appended automatically
         *         route: 'route.name',
         *
         *         // https://keenthemes.com/metronic/preview/demo1/components/icons/flaticon.html
         *         icon: 'flaticon2-list-3',
         *     }
         * ]
         */
        var navs = [
            {
                label: this.intl.t('master'),
                icon: 'flaticon2-list-3',
                subFirst: [
                    {
                        label: this.intl.t('company.identifier'),
                        route: 'master.companies.index'
                    },
                    {
                        label: this.intl.t('customer.identifier'),
                        route: 'master.customers'
                    },
                    {
                        label: this.intl.t('warehouse.identifier'),
                        params: [
                            this.r('master.warehouses.index'),
                            {
                                isQueryParams: true,
                                values: {
                                    company: 'null'
                                }
                            }
                        ]
                    },
                    {
                        label: this.intl.t('staff.identifier'),
                        subSecond: [
                            {
                                label: this.intl.t('staff.identifier'),
                                route: 'master.staffs',
                            },
                            {
                                label: this.intl.t('staff-category.identifier'),
                                route: 'master.staff-categories',
                            },
                            {
                                label: this.intl.t('staff-position.identifier'),
                                route: 'master.staff-positions',
                            },
                        ]

                    },
                    {
                        label: this.intl.t('supplier.identifier'),
                        subSecond: [
                            {
                                label: this.intl.t('supplier.identifier'),
                                route: 'master.suppliers'
                            },
                            {
                                label: this.intl.t('supplier_category.identifier'),
                                route: 'master.supplier-categories',
                            }
                        ]

                    },
                    {
                        label: this.intl.t('service.identifier'),
                        subSecond: [
                            {
                                label: this.intl.t('service.identifier'),
                                route: 'master.services.index'
                            },
                            {
                                label: this.intl.t('service_category.identifier'),
                                route: 'master.service-categories',
                            }
                        ]

                    },
                    {
                        label: this.intl.t('expedition.identifier'),
                        subSecond: [
                            {
                                label: this.intl.t('expedition.identifier'),
                                route: 'master.expeditions'
                            },
                            {
                                label: this.intl.t('expedition_category.identifier'),
                                route: 'master.expedition-categories',
                            },
                        ]

                    },
                    {
                        label: this.intl.t('product.identifier'),
                        subSecond: [
                            {
                                label: this.intl.t('product.identifier'),
                                route: 'master.products'
                            },
                            {
                                label: this.intl.t('product_category.identifier'),
                                route: 'master.product-categories.index',
                            },
                        ]
                    },
                    {
                        label: this.intl.t('product_meta_field.identifier'),
                        route: 'master.product-meta-fields'
                    },
                    {
                        label: this.intl.t('user.identifier'),
                        route: 'master.users'
                    },
                    {
                        label: this.intl.t('role.identifier'),
                        route: 'master.roles'
                    },
                    {
                        label: this.intl.t('stock_division.identifier'),
                        route: 'master.stock-divisions'
                    },
                    {
                        label: this.intl.t('payment_method.identifier'),
                        route: 'master.payment-methods'
                    },
                    {
                        label: this.intl.t('wallet.identifier'),
                        route: 'master.wallets'
                    },
                ]
            },
            {
                label: this.intl.t('sell'),
                icon: 'flaticon-price-tag',
                subFirst: [
                    {
                        label: this.intl.t('sales_order.identifier'),
                        route: 'sales-orders.index'
                    },
                    {
                        label: this.intl.t('sales_receipt.identifier'),
                        route: 'sales-receipts.index'
                    },
                    {
                        label: this.intl.t('prepare_or_return_product.identifier'),
                        route: 'prepare-or-return-product.index'
                    },
                ]
            },
            {
                label: this.intl.t('purchase'),
                icon: 'flaticon2-shopping-cart-1',
                subFirst: [
                    {
                        label: this.intl.t('request_order.identifier'),
                        route: 'purchases.request-orders'
                    },
                    {
                        label: this.intl.t('pre_order.identifier'),
                        route: 'purchases.pre-orders'
                    },
                    {
                        label: this.intl.t('purchase_receipt.identifier'),
                        route: 'purchases.purchase-receipts'
                    },
                ]
            },
        ];

        return navs;
    }),

    actions : {
        onHover(state) {
            state ? $('#quick-add-button').css('width', '16rem') : $('#quick-add-button').css('width', '6.25rem')
        }
    },

    didInsertElement()
    {
        $('body').append('<script src="/assets/metronic/js/scripts.bundle.js"></script>');
    }
});
