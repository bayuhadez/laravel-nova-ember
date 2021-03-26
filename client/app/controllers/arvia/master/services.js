import { isBlank, isEmpty } from '@ember/utils';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';
import Controller from '@ember/controller';
import dtc from 'rcf/utils/dtcolumn';

export default Controller.extend({
    formRecordModel : 'service',
    intl : service(),
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
                name: this.intl.t('service.attr.code'),
                valuePath: 'code',
                filter: {
                    type: 'text',
                    scope: 'codeLike',
                    value: null,
                },
            }),
            dtc.create({
                name: this.intl.t('service.attr.name'),
                valuePath: 'name',
                filter: {
                    type: 'text',
                    scope: 'nameLike',
                }
            }),
            dtc.create({
                name: this.intl.t('service.attr.price'),
                valuePath: 'price',
                filter: {},
            }),
            dtc.create({
                buttons: [
                    {preset: 'edit'},
                    {preset: 'delete'},
                ]
            }),
        ];
    }),

    actions : {
        onSaveRecord() {
            return this.get('formRecord')
                .save()
                .then((service) => {
                    this.get('formRecord.companyServices').forEach(companyService => {
                        if(!isBlank(companyService.get('company.id'))) {
                            companyService.set('service', service);
                            return companyService.save();
                        } else {
                            companyService.rollbackAttributes();
                        }
                    })
                    this.set('refreshDt', true);
                });
        }
    },
});
