import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import dtc from 'rcf/utils/dtcolumn';
import { action, computed } from '@ember/object';
import { isBlank, isEmpty } from '@ember/utils';
import { requestOrder as RoConstants } from 'rcf/constants';

export default class AxxMasterRequestOrdersIndexController extends Controller {
    // services
    @service store;
    @service intl;

    @tracked refreshData = false;
    formRecordModel = 'request-order';

    divisionOptions = [
        { label: 'Wholesale', value: '1' },
        { label: 'Retail', value: '2' }
    ];

    statusOptions = [
        { label: 'RO', value: '0'},
        { label: 'PO', value: '1'},
        { label: 'Draft', value: '2'},
    ];

    @computed(
        'selectedFilterDivision',
        'selectedFilterStatus',
        'columns.@each.filterValue')
    get filterParameters() {
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

    @computed()
    get columns() {
        return [
            dtc.create({
                name: this.intl.t('request_order.rel.company'),
                valuePath: 'company.name',
                filter: {
                    type: 'text',
                    scope: 'companyNameLike',
                }
            }),
            dtc.create({
                name: this.intl.t('request_order.attr.division_name'),
                valuePath: 'company.divisionName',
            }),
            dtc.create({
                name: this.intl.t('request_order.attr.number'),
                valuePath: 'number',
                filter: {
                    type: 'text',
                    scope: 'numberLike',
                }
            }),
            dtc.create({
                name: this.intl.t('request_order.rel.createdBy'),
                valuePath: 'createdBy.fullname',
            }),
            dtc.create({
                name: this.intl.t('request_order.attr.created_at'),
                valuePath: 'formattedCreatedAt',
                filter: {
                    type: 'text',
                    scope: 'createdAtLike',
                }
            }),
            dtc.create({
                name: this.intl.t('request_order.attr.status'),
                valuePath: 'statusLabel',
            }),
            dtc.create({
                buttons: [
                    {preset: 'edit'},
                    {preset: 'delete'},
                ]
            }),
        ];
    }

    @action
    editRequestOrderRecord(record)
    {
        if (record.get('status') != RoConstants.STATUS.PRE_ORDER) {
            this.transitionToRoute('axx.purchases.request-orders.edit', record.get('id'));
        } else {
            alert('Tidak bisa edit RO yg sudah digunakan di Pre Order');
        }
    }

    @action
    deleteRequestOrderRecord(record)
    {
        if (record.get('status') != RoConstants.STATUS.PRE_ORDER) {
            this.qswal.confirmDelete(() => {
                KTApp.blockPage();
                record.destroyRecord().then(() => {
                    KTApp.unblockPage();
                    this.qswal.delete().s();
                    this.set('refreshData', true);
                }).catch(() => {
                    KTApp.unblockPage();
                    this.qswal.delete().e();
                });
            });
        } else {
            alert('Tidak bisa hapus RO yg sudah digunakan di Pre Order');
        }
    }
}
