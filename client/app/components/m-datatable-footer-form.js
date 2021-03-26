import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';

export default Component.extend({
    store: service(),

    tagName: '',
    additionalRows: [],
    footerRows: [],
    formModel: null,
    formModelName: null,   // string|null model name
    parentModel: null,     // object|null of model as the parent of form's model

    didInsertElement()
    {
        this._super(...arguments);

        if (isBlank(this.get('formModel'))) {
            this.createFormModel();
        }
    },

    createFormModel()
    {
        this.set(
            'formModel',
            this.get('store').createRecord(this.get('formModelName'), {
                'tempId': this.generateTempId(),
            })
        );
    },

    generateTempId()
    {
        return this.get("formModelName") + '_' + (new Date().getTime());
    },

    actions: {

        addFooterRow(cellValue, columnValue, rowValue)
        {
            this.onAdd();
            if (
                !isBlank(this.get('formModel').get('validations')) &&
                this.get('formModel').get('validations.isValid')
            ) {
                this.createFormModel();
            }
        },

        deleteFooterRow(rowValue)
        {
            let row = null;

            if (!isBlank(this.get('parentModel')) && this.get('parentModel.isNew')) {
                row = this.store.peekAll(this.get('formModelName'))
                    .find((item) => {
                        return (
                            item.get('isNew') &&
                            item.get('tempId') === rowValue.get('tempId')
                        );
                    });
            } else {
                row = this.store.peekRecord(this.get('formModelName'), rowValue.get('id'));
            }

            this.get('additionalRows').removeObject(row);

            row.destroyRecord();
        }
    },
});
