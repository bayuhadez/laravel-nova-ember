import Controller from '@ember/controller';
import dtc from 'rcf/utils/dtcolumn';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default Controller.extend({
    // services:
    intl: service(),
    store: service(),

    formRecordModel: 'purchase-receipt',
    includeParameters: 'company,transaction-receipt.supplier.company,created-by',

    columns: computed(function () {
        return [
            dtc.create({
                name: this.intl.t('purchase_receipt.rel.company'),
                valuePath: 'company.name',
            }),
            dtc.create({
                name: this.intl.t('company.attr.division'),
                valuePath: 'company.divisionName',
            }),
            dtc.create({
                name: this.intl.t('purchase_receipt.attr.receipted_at'),
                valuePath: 'formattedReceiptAt',
            }),
            dtc.create({
                name: this.intl.t('purchase_receipt.attr.number'),
                valuePath: 'number',
            }),
            dtc.create({
                name: this.intl.t('purchase_receipt.rel.supplier'),
                valuePath: 'transactionReceipt.supplier.company.name',
            }),
            dtc.create({
                name: this.intl.t('purchase_receipt.attr.total'),
                valuePath: 'formattedTotal',
            }),
            dtc.create({
                name: this.intl.t('purchase_receipt.rel.createdBy'),
                valuePath: 'createdBy.fullname',
            }),
            dtc.create({
                buttons: [
                    {preset: 'edit'},
                    {preset: 'delete'},
                ]
            }),
        ];
    }),

    actions: {
    }
});
