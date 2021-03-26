import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import RSVP from 'rsvp';

export default Component.extend({
    store: service(),

    init()
    {
        this._super(...arguments);
        this.formRecord = this.formRecord || null;
        this.isEditingRecord = this.isEditingRecord || false;
    },

    didInsertElement()
    {
        if (typeof this.get('onElementInserted') !== 'undefined') {
            this.get('onElementInserted')(this);
        }
    },

    // methods:
    saveRecord()
    {
        KTApp.blockPage();

        let record = this.get('formRecord');

        let qswal = (
            record.get('isNew') ?
            this.qswal.create() :
            this.qswal.edit()
        );

        return new RSVP.Promise((resolve, reject) => {
            this
                .onSaveRecord()
                .then(() => {
                    KTApp.unblockPage();
                    this.set('isEditingRecord', false);
                    qswal.s().then(() => {
                        resolve();
                    });
                })
                .catch((response) => {
                    KTApp.unblockPage();
                    qswal.e(response).then(() => {
                        reject(response);
                    });
                });
        });
    },

    actions: {
        submit()
        {
            this.saveRecord().then(() => {
                this.afterSaveRecord();
            });
        },
    },
});
