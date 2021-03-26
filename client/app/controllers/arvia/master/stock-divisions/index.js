import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import dtc from 'rcf/utils/dtcolumn';
import { action, computed } from '@ember/object';

export default class AxxMasterStockDivisionsIndexController extends Controller {
    // services
    @service store;
    @service intl;
    @service currentUser;

    @tracked refreshData = false;
    formRecordModel = 'company';

    @computed()
    get filterParameters() {
        return { weAndChildren: this.currentUser.getCompanyIds() };
    }

    @computed()
    get columns() {
        return [
            dtc.create({
                name: this.intl.t('stock_division.rel.company'),
                valuePath: 'name'
            }),
            dtc.create({
                name: this.intl.t('stock_division.attr.division'),
                valuePath: 'divisionName'
            }),
            dtc.create({
                name: this.intl.t('stock_division.attr.totalStockDivisionNames'),
                valuePath: 'savedStockDivisions.length'
            }),
            dtc.create({
                buttons: [
                    {preset: 'edit'},
                ]
            }),
        ];
    }

    @action
    editStockDivisionRecord(record)
    {
        this.transitionToRoute('axx.master.stock-divisions.edit', record.get('id'));
    }

    @action
    deleteStockDivisionRecord(record)
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

