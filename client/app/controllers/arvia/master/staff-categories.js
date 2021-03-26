import Controller from '@ember/controller';
import { isEmpty } from '@ember/utils';
import { inject as service } from '@ember/service';
import dtc from 'rcf/utils/dtcolumn';
import { computed } from '@ember/object';

export default Controller.extend({
    intl: service(),

    formRecordModel: 'staff-category',

    filterParameters: computed(
        'columns.@each.filterValue',
        function () {
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
    ),

    columns: computed(function () {
        return [
            dtc.create({
                name: this.intl.t('staff-category.attr.name'),
                valuePath: 'name',
                filter: {
                    type: 'text',
                    scope: 'nameLike',
                }
            }),
            dtc.create({
                buttons: [
                    {preset: 'edit'},
                    {preset: 'delete'},
                ]
            }),
        ];
    }),

    actions: {
        onSaveRecord()
        {
            return this.get('formRecord').save().then(() => {
                this.set('refreshData', true);
            });
        },
    }

});
