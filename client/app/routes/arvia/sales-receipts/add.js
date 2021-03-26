import Route from '@ember/routing/route';
import { hash } from 'rsvp';
import { inject as service } from '@ember/service';
import { A } from '@ember/array';

export default class AxxSalesReceiptsAddRoute extends Route {

    @service currentUser;

    model()
    {
        return hash({
            salesReceipt: this.store.createRecord('sales-receipt', {
                createdBy: this.currentUser.user,
                createdAt: new Date(),
                receiptedAt: new Date(),
            }),
            transactionReceipt: this.store.createRecord('transaction-receipt'),
            companies: this.store.query('company', {
                filter: { meAndChildren: this.currentUser.currentCompany.get('id') }
            }),
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            salesReceipt: model.salesReceipt,
            transactionReceipt: model.transactionReceipt,
            companies: model.companies,
        });
    }

    willTransition()
    {
        this.controller.rollbackAttributes();
    }
}
