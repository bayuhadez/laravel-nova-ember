import Component from '@ember/component';
import { computed } from '@ember/object';
import { isBlank } from '@ember/utils';

export default Component.extend({

    // default component params
    label: "",
    value: null,
    disabled: false,
    readonly: false,
    isRequired: false,
    showErrors: false,
    errorMessages: "",
    placeholder: "",
    format: "DD/MM/YYYY",
    class: computed('showErrors', 'errorMessages', function () {
        if (this.get('showErrors') && !isBlank(this.get('errorMessages'))) {
            return 'form-control is-invalid';
        }
        return 'form-control';
    }),

    // default onSelection function
    onSelection() {},

    actions: {
        onSelection() {
            this.onSelection();
        }
    }

});
