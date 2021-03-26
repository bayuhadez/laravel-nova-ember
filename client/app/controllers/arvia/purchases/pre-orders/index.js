import Controller from '@ember/controller';
import dtc from 'rcf/utils/dtcolumn';
import Promise, { resolve } from 'rsvp';
import { A } from '@ember/array';
import { action, computed } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank, isEmpty } from '@ember/utils';

export default class AxxPurchasePreOrderIndexController extends Controller {

    @service intl;
    @service store;

    formRecordModel = 'pre-order';
    newPreOrderProducts = [];

    divisionOptions = [
        { label: 'Wholesale', value: '1' },
        { label: 'Retail', value: '2' }
    ];

    statusOptions = [
        { label: 'Belum Selesai', value: '0'},
        { label: 'Selesai', value: '1'},
    ];

    includeParameters = 'company,request-orders,supplier.company,created-by';

    @computed('selectedFilterDivision', 'selectedFilterStatus', 'columns.@each.filterValue')
    get filterParameters()
    {
        let filters = {};

        // filters coming from dt columns
        this.get('columns').forEach(function (column) {
            let filter = column.get('filter');

            if (!isEmpty(column.get('filterValue'))) {
                filters[filter.scope] = column.get('filterValue');
            }
        });

        if (!isBlank(this.get('selectedFilterDivision'))) {
            filters.companyDivisionFilter = this.get('selectedFilterDivision');
        }

        if (!isBlank(this.get('selectedFilterStatus'))) {
            filters.statusFilter = this.get('selectedFilterStatus');
        }

        return filters;
    }

    columns = [
        dtc.create({
            name: this.intl.t('pre_order.rel.company'),
            valuePath: 'company.name',
            filter: {
                type: 'text',
                scope: 'companyNameLike',
            }
        }),
        dtc.create({
            name: this.intl.t('pre_order.attr.division_name'),
            valuePath: 'company.divisionName',
        }),
        dtc.create({
            name: this.intl.t('pre_order.attr.number'),
            valuePath: 'number',
            filter: {
                type: 'text',
                scope: 'numberLike',
            }
        }),
        dtc.create({
            name: this.intl.t('pre_order.rel.createdBy'),
            valuePath: 'createdBy.fullname',
            filter: {
                type: 'text',
                scope: 'createdByNameLike',
            }
        }),
        dtc.create({
            name: this.intl.t('pre_order.attr.ordered_at'),
            valuePath: 'formattedOrderedAt',
        }),
        dtc.create({
            name: this.intl.t('pre_order.rel.supplier'),
            valuePath: 'supplier.company.name',
            filter: {
                type: 'text',
                scope: 'supplierCompanyNameLike',
            }
        }),
        dtc.create({
            name: this.intl.t('pre_order.attr.total'),
            valuePath: 'total',
            filter: {
                type: 'text',
                scope: 'totalLike',
            }
        }),
        dtc.create({
            name: this.intl.t('pre_order.attr.status'),
            valuePath: 'statusLabel',
        }),
        dtc.create({
            buttons: [
                {preset: 'edit'},
                {
                    preset: 'buttonAction',
                    class: 'btn btn-icon btn-circle btn-danger',
                    icon: 'flaticon-delete',
                    onAction: this.deletePreOrder
                },
            ]

        }),
    ];

    @action
    onSaveRecord()
    {
        let self = this;

        return new Promise(function(resolve, reject) {

            // save preOrder
            return self.get('formRecord')
                .save()
                .then((preOrder) => {

                    // set relation preOrder to all newPreOrderProducts
                    self.get('newPreOrderProducts').forEach(function (preOrderProduct) {
                        preOrderProduct.set('preOrder', preOrder);
                    });

                    // save all newPreOrderProducts
                    Promise.all(
                        self.get('newPreOrderProducts').map(preOrderProduct => preOrderProduct.save())
                    ).then(() => {
                        // refresh preOrderProducts
                        self.set('refreshData', true);

                        // set empty newPreOrderProducts
                        self.set('newPreOrderProducts', []);

                        resolve(preOrder);
                    });

                })
                .catch((err) => {
                    reject(err);
                });
        });
    }

    @action
    afterOnModalHidden()
    {
        // remove all unsaved preOrderProducts
        this.get('store')
            .peekAll('request-order-product')
            .filterBy('isNew', true)
            .forEach(function (preOrderProduct) {
                preOrderProduct.deleteRecord();
            });

        // set empty newPreOrderProducts
        this.set('newPreOrderProducts', []);
    }

    @action
    deletePreOrder(preOrder)
    {
        this.qswal.confirmDelete(() => {

            KTApp.blockPage();
            let promises = A();

            // update all RO
            preOrder.get('requestOrders').then((requestOrders) => {
                requestOrders.forEach((ro) => {
                    ro.set('status', 0);
                    ro.set('preOrder', null);
                    promises.pushObject(ro.save());
                });
            });

            return Promise.all(promises).then(() => {

                preOrder.destroyRecord().then(() => {
                    KTApp.unblockPage();
                    this.qswal.delete().s();
                }).catch(() => {
                    KTApp.unblockPage();
                    this.qswal.delete().e();
                });
            });
        });
    }
}
