import Route from '@ember/routing/route';
import { inject as service } from '@ember/service';
import { hash } from 'rsvp';

export default class AxxPurchasesPreOrdersEdit2Route extends Route {

    @service currentUser;

    model(params)
    {
        return hash({
            preOrder: this.store.findRecord('pre-order', params.pre_order_id, {
                include: 'request-orders,pre-order-products.product',
            }),
            currencyOptions: this.store.findAll('currency'),
            companyOptions: this.store.query('company', {
                filter: { meAndChildren: this.currentUser.currentCompany.get('id') }
            }),
            supplierOptions: this.store.findAll('supplier', {
                include: "company,user"
            }),
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            preOrder: model.preOrder,
            currencyOptions : model.currencyOptions,
            companyOptions : model.companyOptions,
            supplierOptions : model.supplierOptions
        });
    }
}
