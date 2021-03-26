import Route from '@ember/routing/route';
import { inject as service } from '@ember/service';
import { hash } from 'rsvp';

export default class AxxPurchasesPreOrdersAddRoute extends Route {

    @service currentUser;

    model()
    {
        return hash({
            preOrder: this.store.createRecord('pre-order', {
                'status': 0,
                'company': this.currentUser.currentCompany,
                'createdBy': this.currentUser.user
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
