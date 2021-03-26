import BaseDatatables from '@ember/component';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default BaseDatatables.extend({
    intl : service(),
    columns : computed(function () {
        return [
            {
                propertyName: 'code',
                title: this.intl.t('expedition.attr.code')
            },
            {
                propertyName : 'name',
                title : this.intl.t('expedition.attr.name')
            },
            {
                propertyName : 'address',
                title : this.intl.t('expedition.attr.address')
            },
            {
                propertyName : 'regency.name',
                title : this.intl.t('expedition.attr.regency')
            },
            {
                propertyName : 'currency.currency',
                title : this.intl.t('expedition.rel.currency')
            },
            {
                propertyName: 'fax.phonecodeAndNumber',
                title : this.intl.t('expedition.rel.fax'),
            },
            {
                propertyName: 'mobilephone.phonecodeAndNumber',
                title: this.intl.t('expedition.rel.mobilephone'),
            },
            {
                propertyName: 'pic',
                title: this.intl.t('expedition.attr.pic'),
            },
            {
                propertyName: 'telephone.phonecodeAndNumber',
                title: this.intl.t('expedition.rel.telephone'),
            },
            {
                propertyName: 'createdAt',
                title: this.intl.t('expedition.attr.created_at'),
            },
            {
                propertyName: 'expeditionCategory.name',
                title: this.intl.t('expedition.rel.expeditionCategory'),
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
