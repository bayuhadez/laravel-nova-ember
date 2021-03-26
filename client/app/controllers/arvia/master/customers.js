import Controller from '@ember/controller';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default Controller.extend({
    formRecordModel: 'customer',
    store : service(),
    intl : service(),

    columns: computed(function () {
        return [
            {
                name: this.intl.t('name'),
                valuePath: 'fullName',
            },
            {
                name: this.intl.t('customer.attr.code'),
                valuePath: 'code',
            },
            {
                name: this.intl.t('customer.attr.telephone_number'),
                valuePath: 'telephoneNumber',
            },
            {
                name: this.intl.t('customer.attr.address'),
                valuePath: 'address',
            },
            {
                buttons: [
                    {preset: 'edit'},
                    {preset: 'delete'},
                ]
            },
        ];
    }),

    actions: {
        onSaveRecord()
        {
            return this.get('formRecord').save();
        },
    }
});
