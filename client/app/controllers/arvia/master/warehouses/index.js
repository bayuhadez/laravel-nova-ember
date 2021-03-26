import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { computed } from '@ember/object';
import dtc from 'rcf/utils/dtcolumn';

export default Controller.extend({
    // queryParams
    queryParams: ['filterCompany'],
    filterCompany: null,
    // services
    store : service(),
    intl: service(),

    refreshDt: false,
    columns: computed(function () {
        return [
            dtc.create({
                name: this.intl.t('warehouse.attr.code'),
                valuePath: 'code',
            }),
            dtc.create({
                name: this.intl.t('warehouse.attr.name'),
                valuePath: 'name',
            }),
            dtc.create({
                name: this.intl.t('warehouse.attr.totalRack'),
                valuePath: 'racks.length',
            }),
            dtc.create({
                name: this.intl.t('warehouse.rel.companies'),
                valuePath: 'listCompanies'
            }),
            dtc.create({
                name: this.intl.t('warehouse.attr.totalQuantity'),
                valuePath: 'totalQuantity',
            }),
            dtc.create({
                buttons: [
                    {preset: 'edit'},
                    {preset: 'delete'},
                ]
            }),
        ];
    }),

    filterParameters: computed('filterCompany', function () {
        let filters = {};
        let companyId = this.get('filterCompany');
        if (!isBlank(companyId) && !isNaN(companyId)) {
            filters.inCompany = companyId;
        }
        return filters;
    }),

    includeParameters: 'companies,racks',

    actions: {
        editWarehouse(record)
        {
            this.transitionToRoute('axx.master.warehouses.edit', record.get('id'));
        },
        deleteWarehouse(record)
        {
            this.qswal.confirmDelete(() => {
                KTApp.blockPage();
                record.destroyRecord().then(() => {
                    KTApp.unblockPage();
                    this.qswal.delete().s();
                    this.set('refreshDt', true);
                }).catch(() => {
                    KTApp.unblockPage();
                    this.qswal.delete().e();
                });
            });
        },
    }
});
