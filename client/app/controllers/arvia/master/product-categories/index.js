import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { computed } from '@ember/object';
import { A } from '@ember/array';
import { hash } from 'rsvp';
import dtc from 'rcf/utils/dtcolumn';

export default Controller.extend({
    formRecordModel: 'product-category',
    intl : service(),

    columns: computed(function () {
        return [
            dtc.create({
                name: this.intl.t('product_category.attr.name'),
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

    actions: {
        onSaveRecord()
        {
            return this.get('formRecord').save().then((parent) => {
                let promises = A();

                parent.get('children').forEach((child) => {
                    promises.pushObject(child.save());
                });

                return hash(promises);
            });
        },
    }

});
