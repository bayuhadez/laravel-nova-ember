import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { computed } from '@ember/object';

export default Component.extend({
    intl : service(),

    columns : computed(function() {
        return [
            {
                propertyName : "name",
                title : this.intl.t('service_category.attr.name')
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
        ]
    })
});
