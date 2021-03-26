import BaseDatatable from 'rcf/components/datatables/base-datatable';
import { computed } from '@ember/object';

export default BaseDatatable.extend({
    columns: computed(function () {
        return [
            {
                propertyName: 'code',
                title: this.intl.t('staff.attr.code'),
            },
            {
                propertyName: 'person.firstName',
                title: this.intl.t('person.attr.first_name'),
            },
            {
                propertyName: 'person.lastName',
                title: this.intl.t('person.attr.last_name'),
            },
            {
                propertyName: 'person.address',
                title: this.intl.t('person.attr.address'),
            },
            {
                propertyName: 'person.phone',
                title: this.intl.t('person.attr.phone'),
            },
            {
                propertyName: 'person.regency.name',
                title: this.intl.t('person.rel.regency'),
            },
            {
                propertyName: 'listCompanies',
                title: this.intl.t('staff.rel.companies'),
            },
            {
                propertyName: 'listStaffCategories',
                title: this.intl.t('staff.rel.staffCategories'),
            },
            {
                propertyName: 'listStaffPositions',
                title: this.intl.t('staff.rel.staffPositions'),
            },
            {
                component: 'editButton',
                propertyName: 'edit',
                disableFiltering: true,
                disableSorting: true,
                title: '',
            },
            {
                component: 'deleteButton',
                propertyName: 'delete',
                disableFiltering: true,
                disableSorting: true,
                title: '',
            },
        ];
    }),
});
