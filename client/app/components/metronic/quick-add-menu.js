import Component from '@ember/component';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default Component.extend({
    intl : service(),
    router: service(),

    navs : computed(function () {
        let navs = [
            {
                label : this.intl.t('supplier.identifier'),
                subs : [
                    {
                        label : this.intl.t('supplier.heading.add'),
                        route: this.r('master.suppliers'),
                    },
                    {
                        label : this.intl.t('supplier_category.heading.create'),
                    }
                ]
            },
            {
                label : this.intl.t('customer.identifier'),
                subs : [
                    {
                        label : this.intl.t('customer.heading.add'),
                    },
                ]
            },
            {
                label : this.intl.t('company.identifier'),
                subs : [
                    {
                        label : this.intl.t('customer.heading.add'),
                    },
                ]
            }
        ]

        return navs;
    }),

    actions : {
        showMenu() {
            $('#quick-add-menu').appendTo('body').modal('show');
        },

        goToSubRoute(submenu) {
            let modal = $('#quick-add-menu');

            // make sure transitioning happens after modal is hidden to avoid UI issues
            modal.on('hidden.bs.modal', () => {
                let url = this.get('router').urlFor(submenu.route, {queryParams: {create: true}});
                this.get('router').transitionTo(url);
            });

            // hide the modal
            modal.modal('hide');
        },
    }
});
