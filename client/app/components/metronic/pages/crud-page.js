import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';

export default Component.extend({
    store: service(),
    intl: service(),

    // customAddRecord() {},
    didInsertElement()
    {
        if (typeof this.get('onElementInserted') !== 'undefined') {
            this.get('onElementInserted')(this);
        }
    },

    actions: {
        saveRecord(modal)
        {
            KTApp.blockPage();

            let record = this.get('formRecord');

            let qswal = (
                record.get('isNew')
                ? this.qswal.create()
                : this.qswal.edit()
            );

            return this
                .onSaveRecord()
                .then(() => {
                    KTApp.unblockPage();
                    modal.modal('hide');
                    this.set('isEditingRecord', false);
                    qswal.s();
                })
                .catch((response) => {
                    KTApp.unblockPage();
                    qswal.e(response.errors[0]);
                });
        },

        addRecord()
        {
            let customAddRecord = this.get('customAddRecord');
            let formRecord = null;

            if (!isBlank(this.get('customAddRecord'))) {
                formRecord = customAddRecord();
            } else {
                formRecord = this.store.createRecord(this.get('modelName'));
            }

            this.setProperties({
                isEditingRecord: true,
                formRecord: formRecord,
            });

        },

        editRecord(formRecord)
        {
            this.setProperties({
                isEditingRecord: true,
                formRecord: formRecord,
            });
        },

        deleteRecord(record)
        {
            this
                .qswal
                .confirmDelete(() => {
                    KTApp.blockPage();
                    record
                        .destroyRecord()
                        .then(() => {
                            KTApp.unblockPage();
                            this.qswal.delete().s();
                        })
                        .catch((response) => {
                            KTApp.unblockPage();
                            this.qswal.delete().e(response.errors[0]);
                        });
                });
        },

        onModalHidden()
        {
            if (
                !isBlank(this.get('formRecord').rollback)
                && typeof this.get('formRecord').rollback === 'function'
            ) {
                this.get('formRecord').rollback();
            } else {
                this.get('formRecord').rollbackAttributes();
            }
            this.set('isEditingRecord', false);

            if (!isBlank(this.afterOnModalHidden)) {
                this.afterOnModalHidden();
            }
        }
    }
});
