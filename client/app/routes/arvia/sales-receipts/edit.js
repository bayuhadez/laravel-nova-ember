import Route from '@ember/routing/route';
import { hash } from 'rsvp';
import { inject as service } from '@ember/service';
import { A } from '@ember/array';

export default class AxxSalesReceiptsEditRoute extends Route {

    @service currentUser;

    async model(params)
    {
        const salesReceipt = await this.store.findRecord('sales-receipt',
            params.sales_receipt_id, {
            include: 'transaction-receipt'
            +',transaction-receipt.service-transaction-receipts'
            +',transaction-receipt.service-transaction-receipts.sales-order-service'
            +',transaction-receipt.product-transaction-receipts'
            +',transaction-receipt.product-transaction-receipts.product-sales-order'
        });

        return hash({
            companies: this.store.query('company', {
                filter: { meAndChildren: this.currentUser.currentCompany.get('id') }
            }),
            salesReceipt: salesReceipt,
            transactionReceipt: salesReceipt.transactionReceipt,
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            companies: model.companies,
            salesReceipt: model.salesReceipt,
            transactionReceipt: model.transactionReceipt,
        });
    }

    willTransition()
    {
        this.controller.rollbackAttributes();
    }
}