import BaseDatatable from 'rcf/components/datatables/base-datatable';
import { computed } from '@ember/object';

export default BaseDatatable.extend({
    columns: computed(function () {
        return [
            {
                propertyName: 'company.name',
                title: this.intl.t('company_customer.rel.company'),
            },
            {
                propertyName: 'creditLimit',
                title: this.intl.t('company_customer.attr.credit_limit'),
            },
            {
                propertyName: 'discount',
                title: this.intl.t('company_customer.attr.discount'),
            },
            {
                propertyName: 'termOfPayment',
                title: this.intl.t('company_customer.attr.term_of_payment'),
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
