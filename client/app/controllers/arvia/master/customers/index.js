import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import dtc from 'rcf/utils/dtcolumn';
import { action } from '@ember/object';

export default class AxxMasterCustomersIndexController extends Controller {
    // services
    @service store;
    @service intl;

    @tracked refreshDt = false;

    columns = [
        dtc.create({
            name: this.intl.t('customer.attr.name'),
            valuePath: 'displayName',
        }),
        dtc.create({
            name: this.intl.t('customer.attr.telephone_number'),
            valuePath: 'telephoneNumber',
        }),
        dtc.create({
            name: this.intl.t('address.attr.address'),
            valuePath: 'defaultCustomerAddressName',
        }),
        dtc.create({
            name: this.intl.t('customer.attr.isSubCustomer'),
            valuePath: 'isSubCustomerText',
        }),
        dtc.create({
            buttons: [
                {preset: 'edit'},
                {preset: 'delete'},
            ]
        }),
    ];

    includeParameters = 'company.company-addresses.address,person.person-addresses.address,pic,parent-customer,children-customer';

    @action
    editCustomer(record)
    {
        this.transitionToRoute('axx.master.customers.edit', record.get('id'));
    }

    @action
    deleteCustomer(record)
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

