import BaseDatatables from '@ember/component';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default BaseDatatables.extend({
    intl : service(),
    columns : computed(function() {
        return [
            {
                propertyName: 'code',
                title: this.intl.t('service.attr.code')
            },
            {
                propertyName : 'name',
                title : this.intl.t('service.attr.name')
            },
            {
                propertyName : 'description',
                title : this.intl.t('service.attr.description'),
            },
            {
                propertyName : 'serviceCategory.name',
                title : this.intl.t('service.rel.serviceCategory'),
            },
            {
                component : 'editButton',
                propertyName : 'edit',
                disableFiltering : true,
                disableSorting : true,
                title : '',
            },
            {
                component : 'deleteButton',
                propertyName : 'delete',
                disableFiltering : true,
                disableSorting : true,
                title : '',
            }
        ];
    })
});
