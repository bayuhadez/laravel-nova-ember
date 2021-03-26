import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { action } from '@ember/object';
import { A } from '@ember/array';
import Promise, { resolve, reject } from 'rsvp';

export default class AxxSalesReceiptsEditController extends Controller {
    // services
    @service store;
    @service intl;
    @service currentUser;

    breadcrumbs = {
        title: this.intl.t('sales_receipt.identifier'),
        route: "axx.sales-receipts",
        subNav: [
            {
                name: this.intl.t('sales_receipt.heading.edit'),
            }
        ],
    };

    @action
    saveSalesReceiptForm(salesReceipt, transactionReceipt, event)
    {
        event.preventDefault();
        KTApp.blockPage();

        let ptrp = A();
        let strp = A();
        let psop = A();
        let sosp = A();
        let psmp = A();
        let srp = A();

        // update total and subtotal
        salesReceipt.total = salesReceipt.calculatedTotal;
        salesReceipt.subTotal = salesReceipt.calculatedSubTotal;
        
        return transactionReceipt.save().then((tr) => {
            salesReceipt.transactionReceipt = tr;
            tr.get('productTransactionReceipts').forEach(async (ptr) => {
                let pso = await ptr.get('productSalesOrder');
                if (ptr.isNew) {
                    // this is temporary solution, the correct one psmOut record should be created based on
                    // product_transactionreceipt record data instead of getting lastPsm
                    let sortedProductStockMovements = pso.get('productStockMovements').sortBy('datetime');
                    let lastPsm = sortedProductStockMovements.find((psm) => psm.inOrOut == 1);
                    let psmOut = this.store.createRecord(
                        'product-stock-movement', {
                            quantity: ptr.quantity,
                            inOrOut: 0,
                            product: ptr.product,
                            rack: lastPsm.rack,
                            stockDivision: lastPsm.stockDivision,
                            productSalesOrder: pso,
                            user: this.currentUser.user,
                            customer: tr.customer
                        }
                    );
                    psmp.pushObject(psmOut.save());
                }
                ptrp.pushObject(ptr.save());
                psop.pushObject(pso.save());
            });

            tr.get('serviceTransactionReceipts').forEach(async (str) => {
                strp.pushObject(str.save());
                let sos = await str.get('salesOrderService');
                sosp.pushObject(sos.save());
            });

            srp.pushObject(salesReceipt.save());

            return Promise.all(ptrp, strp, psop, sosp, psmp, srp).then(() => {
                KTApp.unblockPage();
                resolve();
                this.qswal.edit().s();
                this.transitionToRoute('axx.sales-receipts.index');
            }).catch((response) => {
                KTApp.unblockPage();
                this.qswal.edit().e(response);
                reject(response);
            });
        });
    }
}
