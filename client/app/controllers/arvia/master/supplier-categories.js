import Controller from '@ember/controller';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';
import dtc from 'rcf/utils/dtcolumn';

export default Controller.extend({
    formRecordModel : 'supplier-category',
    store : service(),
    intl : service(),

    columns: computed(function () {
        return [
            dtc.create({
                name: this.intl.t('supplier_category.attr.name'),
                valuePath: 'name',
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
            return this.get('formRecord').save();
        }
    }
});
