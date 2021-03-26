import Route from '@ember/routing/route';
import { A } from '@ember/array';
import { action } from '@ember/object';
import { hash } from 'rsvp';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';

export default class AxxPurchasesPurchaseReceiptAddRoute extends Route {

    @service currentUser;

    model()
    {
        return hash({
            companies: this.store.query('company', {
                filter: { id: this.currentUser.getCompanyIds() }
            }),
            suppliers: this.store.query('supplier', {
                include: 'company'
            }),
            purchaseReceipt: this.store.createRecord('purchase-receipt'),
            transactionReceipt: this.store.createRecord('transaction-receipt'),
        });
    }

    setupController(controller, model)
    {
        this._super(controller, model);

        controller.setProperties({
            companies: model.companies,
            formRecord: model.purchaseReceipt,
            isPoEditing: false,
            newProductTransactionReceipts: A(),
            preOrders: A(),
            productTransactionReceiptRows: A(),
            selectedPreOrders: A(),
            suppliers: model.suppliers,
            transactionReceipt: model.transactionReceipt,
        });
    }

    cleanTransactionReceipts()
    {
        this.store
            .peekAll('transaction-receipt')
            .filterBy('isNew', true)
            .invoke('deleteRecord');
    }

    cleanProductTransactionReceipts()
    {
        this.store
            .peekAll('product-transaction-receipt')
            .filterBy('isNew', true)
            .invoke('deleteRecord');
    }

    @action
    willTransition()
    {
        this.controller.formRecord.rollbackAttributes();
        this.cleanProductTransactionReceipts();
        this.cleanTransactionReceipts();
        return true;
    }
}
