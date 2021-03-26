import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { schedule } from '@ember/runloop';
import { alias } from '@ember/object/computed';
import { isBlank } from '@ember/utils';

export default Component.extend({
    router: service(),
	intl: service(),

    // default component properties
    modalId: '',
    title: '',
    size: 'xl',
    useAutoRender: true,
    onSubmit() {},

    init()
    {
        this._super(...arguments);
        this.setProperties({
            submitButtonLabel: this.submitButtonLabel || this.get('intl').t("save"),
        });
    },

    actions: {
        submit()
        {
            let modal = $('#'+this.elementId + ' .modal').first();
            return this.onSubmit(modal);
        },

        afterSave()
        {
        }
    },

    didInsertElement()
    {
        this._super(...arguments);
        let self = this;
        let modal = $('#' + this.elementId + ' .modal').first();
        let useAutoRender = this.get('useAutoRender');

        if (useAutoRender) {
            schedule('afterRender', this, function () {
                modal.modal('show');

                modal.on('hidden.bs.modal', function () {
                    if (!isBlank(self.onHide)) {
                        self.onHide();
                    }
                });
            });
        }

    }
});
