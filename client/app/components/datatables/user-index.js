import BaseDatatable from 'rcf/components/datatables/base-datatable';
import { computed } from '@ember/object';

export default BaseDatatable.extend({
    columns: computed(function () {
        return [
            {
                propertyName: 'email',
                title: this.intl.t('user.attr.email'),
            },
            {
                propertyName: 'username',
                title: this.intl.t('user.attr.username'),
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
