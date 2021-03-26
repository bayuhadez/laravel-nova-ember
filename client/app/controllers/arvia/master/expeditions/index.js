import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import dtc from 'rcf/utils/dtcolumn';
import { action, computed } from '@ember/object';
import { isBlank, isEmpty } from '@ember/utils';

export default class AxxMasterExpeditionsIndexController extends Controller {
    // services
    @service store;
    @service intl;

    @tracked refreshData = false;
    formRecordModel = 'expedition';

    @computed('columns.@each.filterValue')
    get filterParameters() {
        let filters = {};

            // filters coming from dt columns
            this.get('columns').forEach(function (column) {
                let filter = column.get('filter');

                if (!isEmpty(column.get('filterValue'))) {
                    filters[filter.scope] = column.get('filterValue');
                }
            });

            return filters;
    }

    @computed()
    get columns() {
        return [
            dtc.create({
                valuePath: 'code',
                name: this.intl.t('expedition.attr.code')
            }),
            dtc.create({
                valuePath: 'name',
                name: this.intl.t('expedition.attr.name')
            }),
            dtc.create({
                valuePath: 'address',
                name: this.intl.t('expedition.attr.address')
            }),
            dtc.create({
                valuePath: 'regency.name',
                name: this.intl.t('expedition.attr.regency')
            }),
            dtc.create({
                valuePath : 'currency.code',
                name: this.intl.t('expedition.rel.currency')
            }),
            dtc.create({
                valuePath: 'faxNumber',
                name: this.intl.t('expedition.attr.fax_number'),
            }),
            dtc.create({
                valuePath: 'mobileNumber',
                name: this.intl.t('expedition.attr.mobile_number'),
            }),
            dtc.create({
                valuePath: 'pic',
                name: this.intl.t('expedition.attr.pic'),
            }),
            dtc.create({
                valuePath: 'telephoneNumber',
                name: this.intl.t('expedition.attr.telephone_number'),
            }),
            dtc.create({
                valuePath: 'formattedCreatedAt',
                name: this.intl.t('expedition.attr.created_at'),
            }),
            dtc.create({
                valuePath: 'expeditionCategory.name',
                name: this.intl.t('expedition.rel.expeditionCategory'),
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
    editExpeditionRecord(record)
    {
        this.transitionToRoute('axx.master.expeditions.edit', record.get('id'));
    }

    @action
    deleteExpeditionRecord(record)
    {
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
    }
}

