import Component from '@ember/component';
import { isBlank } from '@ember/utils';

export default Component.extend({
    cellValue: {
        component: {
            attributeName: null,
            label: null,
            disabled: false
        }
    },
    columnValue: null,
    rowValue: null,
    cellMeta: null,
    rowMeta: null,
    model: null,
    type: 'text',
    disabled: false,

    didInsertElement()
    {
        this._super(...arguments);

        let model = this.get('model');
        let component = this.get('cellValue.component');
        let params = component.params;

        this.set('params', params);

        // attributeName
        this.set('attributeName', params.attributeName);

        // disabled
        this.set('disabled', params.disabled);

        // label
        if (!isBlank(params.label) && !isBlank(model.get(params.label))) {
            this.set('label', model.get(params.label));
        }
    },
});
