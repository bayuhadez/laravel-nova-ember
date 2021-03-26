import Controller from '@ember/controller';
import dtc from 'rcf/utils/dtcolumn';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import { action, computed } from '@ember/object';
import { isEmpty, isBlank } from '@ember/utils';
import { A } from '@ember/array';

export default class AxxSalesReceiptsIndexController extends Controller {
    // services
    @service store;
    @service intl;
    @service currentUser;

    @tracked refreshData = false;

    @computed('columns.@each.filterValue')
    get filterParameters()
    {
        let filters = {};
        // filter only show sales receipt belong in my company and it child company
        filters.inCompanyAndDescendant = this.currentUser.currentCompany.id;
        // filters coming from dt columns
        this.get('columns').forEach(function (column) {
            let filter = column.get('filter');

            if (!isEmpty(column.get('filterValue'))) {
                filters[filter.scope] = column.get('filterValue');
            }
        });
        return filters;
    }

    includeParameters = 'created-by.person'
    +',transaction-receipt.customer.person'
    +',transaction-receipt.customer.sales-orders.sales.person'
    +',transaction-receipt.service-transaction-receipts'
    +',transaction-receipt.service-transaction-receipts.sales-order-service'
    +',transaction-receipt.product-transaction-receipts'
    +',transaction-receipt.product-transaction-receipts.product-sales-order';

    columns = A([
        dtc.create({
            valuePath: 'formattedCreatedAt',
            name: this.intl.t('sales_receipt.attr.createdAt')
        }),
        dtc.create({
            valuePath: 'number',
            name: this.intl.t('sales_receipt.attr.number')
        }),
        dtc.create({
            valuePath: 'transactionReceipt.customer.displayName',
            name: this.intl.t('sales_receipt.rel.customer')
        }),
        dtc.create({
            valuePath: 'subTotal',
            name: this.intl.t('sales_receipt.attr.subTotal')
        }),
        dtc.create({
            valuePath: 'salesNames',
            name: this.intl.t('sales_receipt.rel.sales')
        }),
        dtc.create({
            valuePath: 'createdBy.fullname',
            name: this.intl.t('sales_receipt.rel.createdBy')
        }),
        dtc.create({
            buttons: [
                {preset: 'edit'},
                {
                    preset: 'buttonAction',
                    class: "btn-icon btn-circle btn-success",
                    icon: 'flaticon2-shopping-cart',
                    onAction: this.pay,
                },
                {preset: 'delete'},
            ]
        }),
    ]);

    @action
    editSalesReceiptRecord(record)
    {
        this.transitionToRoute('axx.sales-receipts.edit', record.get('id'));
    }

    @action
    pay(record)
    {
        // TODO
        console.log(record);
    }

    @action
    deleteSalesReceiptRecord(record)
    {
        this.qswal.confirmDelete(async () => {
            KTApp.blockPage();
            if(!isBlank(record.get('transactionReceipt.productTransactionReceipts'))) {
                await record.get('transactionReceipt.productTransactionReceipts').forEach(ptr => {
                    ptr.destroyRecord();
                });
            }
            if(!isBlank(record.get('transactionReceipt.serviceTransactionReceipts'))) {
                await record.get('transactionReceipt.serviceTransactionReceipts').forEach(str => {
                    str.destroyRecord();
                });
            }
            let transactionReceipt = await record.get('transactionReceipt');
            record.destroyRecord().then(() => {
                transactionReceipt.destroyRecord().then(() => {
                    KTApp.unblockPage();
                    this.qswal.delete().s();
                    this.set('refreshData', true);
                }).catch(() => {
                    KTApp.unblockPage();
                    this.qswal.delete().e();
                });
            }).catch(() => {
                KTApp.unblockPage();
                this.qswal.delete().e();
            });
        });
    }

}

