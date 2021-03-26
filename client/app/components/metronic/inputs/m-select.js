import Component from '@ember/component';
import { isBlank } from '@ember/utils';
import { computed } from '@ember/object';
import { isArray } from '@ember/array';

export default Component.extend({

    // default component propertiess
    allowClear: false,
    componentName: "power-select",
    disabled: false,
    placeholder: "",
    renderInPlance: true,
    searchEnabled: true,
    showErrors: false,
    errorMessages: "",
    useInputWrapper: true,

    onchange() {},

    triggerClass: computed('showErrors', 'errorMessages', function () {
        if (this.get('showErrors') && !isBlank(this.get('errorMessages'))) {
            return 'form-control is-invalid';
        }
        return 'form-control';
    }),

    isErrorMessagesArray: computed('errorMessages', function() {
        return isArray(this.get('errorMessages'));
    }),

    actions: {
        onkeydown(select, e)
        {
            let term = select.searchText;

            if (
                // add item action is defined
                !isBlank('addItem')
                // key pressed is enter
                && (e.keyCode === 13)
                // power select dropdown is in open state
                && select.isOpen
                // no currently highlighted option
                && !select.highlighted
                // passed search term is not blank
                && !isBlank(term)
            ) {
                this.get('addItem')(term)
                .then(function (newItem) {
                    // select the new item
                    select.actions.choose(newItem);
                });
            }
        }
    },

    didReceiveAttrs()
    {
        if (this.get('selectType') === "multiple") {
            this.set('componentName', "power-select-multiple");
        }
    }
});
