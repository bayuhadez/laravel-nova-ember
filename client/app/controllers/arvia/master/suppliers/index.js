import Controller from '@ember/controller';
import dtc from 'rcf/utils/dtcolumn';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import { computed } from '@ember/object';

export default class AxxMasterSuppliersIndexController extends Controller {
    // services
    @service store;
    @service intl;

    refreshDt = false;

    get filterParameters () {
        return {};
    }

    columns = [
        dtc.create({
            name: this.intl.t('supplier.attr.name'),
            valuePath: 'company.name',
            /*
                filter: {
                    type: 'text',
                    scope: 'companyNameLike',
                },
                */
        }),
        dtc.create({
            name: this.intl.t('address.attr.address'),
            valuePath: 'address',
        }),
        dtc.create({
            name: this.intl.t('address.rel.regency'),
            valuePath: 'regency',
        }),
        dtc.create({
            name: this.intl.t('supplier.rel.currency'),
            valuePath: 'currency.code',
            /*
                filter: {
                    type: 'text',
                    scope: 'currencyCode',
                },
                */
        }),
        dtc.create({
            valuePath: 'mobileNumber',
            name: this.intl.t('company.attr.mobile_number'),
        }),
        dtc.create({
            valuePath: 'telephoneNumber',
            name: this.intl.t('company.attr.telephone_number'),
        }),
        dtc.create({
            valuePath: 'faxNumber',
            name: this.intl.t('company.attr.fax_number'),
        }),
        dtc.create({
            valuePath: 'pic.fullname',
            name: this.intl.t('supplier.attr.pic'),
        }),
        dtc.create({
            valuePath: 'top',
            name: this.intl.t('supplier.attr.top')
        }),
        dtc.create({
            valuePath: 'formattedPlafon',
            name: this.intl.t('supplier.attr.plafon')
        }),
        dtc.create({
            valuePath: 'formattedCreatedAt',
            name: this.intl.t('supplier.attr.createdAt'),
        }),
        dtc.create({
            buttons: [
                {preset: 'edit'},
                {preset: 'delete'},
            ]
        }),
    ];

    includeParameters = 'company,currency';

    @action
    editSupplier(record)
    {
        this.transitionToRoute('axx.master.suppliers.edit', record.get('id'));
    }

    @action
    deleteSupplier(record)
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
    }
}
