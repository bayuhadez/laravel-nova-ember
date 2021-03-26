import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import dtc from 'rcf/utils/dtcolumn';
import { action, computed } from '@ember/object';
import { isBlank, isEmpty } from '@ember/utils';

export default class AxxMasterUsersIndexController extends Controller {
    // services
    @service store;
    @service intl;

    @tracked refreshData = false;
    formRecordModel = 'user';

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
                name: this.intl.t('user.attr.email'),
                valuePath: 'email',
            }),
            dtc.create({
                name: this.intl.t('user.attr.username'),
                valuePath: 'username',
            }),
            dtc.create({
                name: this.intl.t('person.attr.first_name'),
                valuePath: 'person.firstName',
            }),
            dtc.create({
                name: this.intl.t('person.attr.last_name'),
                valuePath: 'person.lastName',
            }),
            dtc.create({
                name: this.intl.t('person.attr.address'),
                valuePath: 'person.address',
            }),
            dtc.create({
                name: this.intl.t('person.attr.phone'),
                valuePath: 'person.phone',
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
    editUserRecord(record)
    {
        this.transitionToRoute('axx.master.users.edit', record.get('id'));
    }

    @action
    deleteUserRecord(record)
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

