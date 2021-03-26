import BaseDatatable from 'rcf/components/datatables/base-datatable';
import { computed } from '@ember/object';

export default BaseDatatable.extend({
    columns: computed(function () {
        return [
            {
                propertyName: 'name',
                title: this.intl.t('expedition.attr.name'),
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
