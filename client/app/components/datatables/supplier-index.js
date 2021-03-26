import BaseDatatable from '@ember/component';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default BaseDatatable.extend({
    intl : service(),
    columns : computed(function() {
        return [
            {
                propertyName : 'name',
                title : this.intl.t('supplier.attr.name')
            },
            {
                propertyName : 'address',
                title : this.intl.t('supplier.attr.address')
            },
            {
                propertyName : 'regency.name',
                title : this.intl.t('supplier.rel.regency')
            },
            {
                propertyName : 'currency.currency',
                title : this.intl.t('supplier.rel.currency')
            },
            {
                propertyName : 'mobilephone.phonecodeAndNumber',
                title : this.intl.t('supplier.rel.mobilephone'),
            },
            {
                propertyName : 'telephone.phonecodeAndNumber',
                title : this.intl.t('supplier.rel.telephone'),
            },
            {
                propertyName : 'fax.number',
                title : this.intl.t('supplier.rel.fax'),
            },
            {
                propertyName : 'pic',
                title : this.intl.t('supplier.attr.pic')
            },
            {
                propertyName : 'createdAt',
                title : this.intl.t('supplier.attr.createdAt'),
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
        ]
    }),
});
