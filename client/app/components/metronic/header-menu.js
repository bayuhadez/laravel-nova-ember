import Component from '@ember/component';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default Component.extend({
    intl: service(),

    classNames: ['kt-header-menu-wrapper','kt-grid__item', 'kt-grid__item--fluid'],

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
         *         // use this only for navs without subs
         *         route: 'route.name',
         *
         *         // optional, sub navs. if this is specified it will ignore
         *         // the specified route
         *         subs: array of nav
         *
         *     }
         * ]
         */
        var navs = [
            {
                label: this.intl.t('company.identifier'),
                subs: [
                    {
                        label: this.intl.t('list'),
                        route: 'master.companies',
                    },
                    {
                        label: this.intl.t('create'),
                        route: 'master.companies.create',
                    },
                ]
            },
            {
                label : this.intl.t('staff.identifier'),
                subs: [
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
                label : this.intl.t('supplier.identifier'),
                subs : [
                    {
                        label : this.intl.t('supplier.identifier'),
                        route : 'master.suppliers'
                    },
                    {
                        label : this.intl.t('supplier_category.identifier'),
                        route : 'master.supplier-categories',
                    }
                ]
            },
            {
                label : this.intl.t('service.identifier'),
                subs : [
                    {
                        label : this.intl.t('service.identifier'),
                        route : 'master.services'
                    },
                    {
                        label : this.intl.t('service_category.identifier'),
                        route : 'master.service-categories',
                    }
                ]
            },
            {
                label : this.intl.t('expedition.identifier'),
                subs : [
                    {
                        label: this.intl.t('expedition.identifier'),
                        route : 'master.expeditions'
                    },
                    {
                        label: this.intl.t('expedition_category.identifier'),
                        route: 'master.expedition-categories',
                    },
                ]
            },
            {
                label: this.intl.t('product.identifier'),
                route: 'master.products'
            },
            {
                label: this.intl.t('customer.identifier'),
                route: 'master.customers'
            },
            {
                label: this.intl.t('product_meta_field.identifier'),
                route: 'master.product-meta-fields'
            },
            {
                label: this.intl.t('warehouse.identifier'),
                route: 'master.warehouses'
            },
            {
                label: this.intl.t('user.identifier'),
                route: 'master.users'
            },

        ];

        return navs;
    }),
});
