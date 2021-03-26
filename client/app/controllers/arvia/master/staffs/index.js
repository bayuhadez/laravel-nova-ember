import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import dtc from 'rcf/utils/dtcolumn';
import { action, computed } from '@ember/object';
import { isEmpty } from '@ember/utils';

export default class AxxMasterStaffsIndexController extends Controller {
    // services
    @service store;
    @service intl;

    @tracked refreshData = false;

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
                name: this.intl.t('staff.attr.code'),
                filter: {
                    type: 'text',
                    scope: 'codeLike',
                }
            }),
            dtc.create({
                valuePath: 'person.firstName',
                name: this.intl.t('person.attr.first_name'),
                filter: {
                    type: 'text',
                    scope: 'personFirstNameLike',
                }
            }),
            dtc.create({
                valuePath: 'person.lastName',
                name: this.intl.t('person.attr.last_name'),
                filter: {
                    type: 'text',
                    scope: 'personLastNameLike',
                }
            }),
            dtc.create({
                valuePath: 'person.defaultAddress',
                name: this.intl.t('person.attr.address'),
                filter: {
                    type: 'text',
                    scope: 'personAddressLike',
                }
            }),
            dtc.create({
                valuePath: 'person.telephoneNumber',
                name: this.intl.t('person.attr.phone'),
                filter: {
                    type: 'text',
                    scope: 'personPhoneLike',
                }
            }),
            dtc.create({
                valuePath: 'person.regency.name',
                name: this.intl.t('person.rel.regency'),
                filter: {
                    type: 'text',
                    scope: 'personRegencyNameLike',
                }
            }),
            dtc.create({
                valuePath: 'company.name',
                name: this.intl.t('staff.rel.company'),
                filter: {
                    type: 'text',
                    scope: 'companyNameLike',
                }
            }),
            dtc.create({
                valuePath: 'listStaffCategories',
                name: this.intl.t('staff.rel.staffCategories'),
                filter: {
                    type: 'text',
                    scope: 'staffCategoryNameLike',
                }
            }),
            dtc.create({
                valuePath: 'listStaffPositions',
                name: this.intl.t('staff.rel.staffPositions'),
                filter: {
                    type: 'text',
                    scope: 'staffPositionNameLike',
                }
            }),
            dtc.create({
                buttons: [
                    {preset: 'edit'},
                    {preset: 'delete'},
                ]
            }),
        ];
    }

    includeParameters = 'company,person,person.regency';

    @action
    editStaffRecord(record)
    {
        this.transitionToRoute('axx.master.staffs.edit', record.get('id'));
    }

    @action
    deleteStaffRecord(record)
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

