import Controller from '@ember/controller';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import dtc from 'rcf/utils/dtcolumn';

export default class AxxMasterServicesIndexController extends Controller {

    @service intl;
    @tracked refreshDt = false;

    get filterParameters() {
        return {};
    }

    columns = [
       dtc.create({
            name: this.intl.t('service.attr.code'),
            valuePath: 'code',
            filter: {
                type: 'text',
                scope: 'codeLike',
                value: null,
            },
        }),
        dtc.create({
            name: this.intl.t('service.attr.name'),
            valuePath: 'name',
            filter: {
                type: 'text',
                scope: 'nameLike',
            }
        }),
        dtc.create({
            name: this.intl.t('service.attr.price'),
            valuePath: 'price',
            filter: {},
        }),
        dtc.create({
            buttons: [
                {preset: 'edit'},
                {preset: 'delete'},
            ]
        }), 
    ];

    @action
    redirectToListWarehouse(record)
    {
        // redirect to list warehouses with company filter
        this.transitionToRoute('axx.master.warehouses.index', { queryParams: { filterCompany: record.get('id') }});
    }

    @action
    editRecord(record)
    {
        this.transitionToRoute('axx.master.services.edit', record.id);
    }

    @action
    deleteRecord(record)
    {
        this.qswal.confirmDelete(() => {
            KTApp.blockPage();
            record.destroyRecord().then(() => {
                KTApp.unblockPage();
                this.qswal.delete().s();
            }).catch((err) => {
                KTApp.unblockPage();
                this.qswal.delete().e();
            });
        });
    }
}
