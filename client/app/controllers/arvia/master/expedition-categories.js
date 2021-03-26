import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { computed } from '@ember/object';
import dtc from 'rcf/utils/dtcolumn';

export default Controller.extend({
    formRecordModel: 'expedition-category',
    store: service(),
    intl: service(),

    columns: computed(function () {
        return [
            dtc.create({
                name: this.intl.t('expedition.attr.name'),
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
        },
    }

});
