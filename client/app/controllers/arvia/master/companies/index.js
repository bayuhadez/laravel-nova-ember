import Controller from '@ember/controller';
import { action } from '@ember/object';
import { isBlank } from '@ember/utils';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import dtc from 'rcf/utils/dtcolumn';

export default class AxxMasterCustomersIndexController extends Controller {

    @service intl;
    @service currentUser;
    @tracked refreshDt = false;

    includeParameters = 'parent-company,warehouses,customers';

    get filterParameters() {
        return { weAndChildren: this.currentUser.getCompanyIds() };
    }

    columns = [
        dtc.create({
            name: this.intl.t('company.attr.code'),
            valuePath: 'code',
            routeLink: 'master.companies.edit',
            useRowModelAsParam: true
        }),
        dtc.create({
            name: this.intl.t('company.attr.name'),
            valuePath: 'name',
            routeLink: 'master.companies.edit',
            useRowModelAsParam: true
        }),
        dtc.create({
            name: this.intl.t('company.rel.parentCompany'),
            valuePath: 'parentCompany.name',
            routeLink: 'master.companies.edit',
            useRowModelAsParam: true
        }),
        dtc.create({
            name: this.intl.t('company.attr.total_warehouses'),
            valuePath: 'warehouses.length',
            useRouteLinkAction: true
        }),
        dtc.create({
            name: this.intl.t('company.attr.valueAddedTaxType'),
            valuePath: 'valueAddedTaxTypeName',
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
    editCompanyRecord(record)
    {
        this.transitionToRoute('axx.master.companies.edit', record.id);
    }

    @action
    deleteCompanyRecord(record)
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
