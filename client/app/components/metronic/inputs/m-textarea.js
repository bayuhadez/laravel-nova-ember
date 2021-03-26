import Component from '@ember/component';
import { computed } from '@ember/object';
import { isBlank } from '@ember/utils';

export default Component.extend({

    // default properties
    label: "",
    disabled: false,
    readonly: false,
    isRequired: false,
    showErrors: false,
    errorMessages: "",
    placeholder: "",
    rows: 3,

    init()
    {
        this._super(...arguments);
        this.setProperties({
            errors: [],
        });
    },

    hasErrors: computed('errors.[]', function () {
        let errors = this.get('errors');
        return (errors && errors.length > 0);
    }),

    inputClass: computed('showErrors', 'errorMessages', function () {
        if (this.get('showErrors') && !isBlank(this.get('errorMessages'))) {
            return 'form-control is-invalid';
        }
        return 'form-control';
    })
});
