import BaseDatatable from 'rcf/components/datatables/base-datatable';
import { computed } from '@ember/object';

export default BaseDatatable.extend({
    columns: computed(function () {
        return [
            {
                propertyName: 'fullName',
                title: this.intl.t('name'),
            },
            {
                propertyName: 'code',
                title: this.intl.t('customer.attr.code'),
            },
            {
                propertyName: 'telephone.phonecodeAndNumber',
                title: this.intl.t('customer.rel.telephone'),
            },
            {
                propertyName: 'address',
                title: this.intl.t('customer.attr.address'),
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
