import Route from '@ember/routing/route';
import AxxPurchasesPurchaseReceiptAddRoute from 'rcf/routes/axx/purchases/purchase-receipts/add';
import { A } from '@ember/array';
import { hash } from 'rsvp';

export default class AxxPurchasesPurchaseReceiptsEditRoute extends AxxPurchasesPurchaseReceiptAddRoute {
    templateName = 'axx/purchases/purchase-receipts/add';

    async model(params)
    {
        const purchaseReceipt = await this.store.findRecord(
            'purchase-receipt',
            params.purchase_receipt_id,
            { include: 'company,transaction-receipt.supplier.company,created-by.person,currency' }
        );

        return hash({
            companies: this.store.query('company', {
                filter: { id: this.currentUser.getCompanyIds() }
            }),
            suppliers: this.store.query('supplier', {
                include: 'company'
            }),
            purchaseReceipt: purchaseReceipt,
            transactionReceipt: purchaseReceipt.transactionReceipt,
        });
    }

    setupController(controller, model)
    {
        this._super(controller, model);

        controller.setProperties({
            isPoEditing: false,
            preOrders: A([]),
            selectedPreOrders: A([]),
            companies: model.companies,
            suppliers: model.suppliers,
            formRecord: model.purchaseReceipt,
            transactionReceipt: model.transactionReceipt,
            newProductTransactionReceipts: A(),
        });
    }
}
