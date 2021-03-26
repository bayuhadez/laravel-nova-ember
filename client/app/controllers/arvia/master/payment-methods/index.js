import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import dtc from 'rcf/utils/dtcolumn';
import { action } from '@ember/object';
import { A } from '@ember/array';

export default class AxxMasterPaymentMethodIndexController extends Controller {
    // services
    @service store;
    @service intl;

    @tracked refreshData = false;
    @tracked isEditingPaymentMethod = false;
    @tracked paymentMethodSource;

    formRecordModel = 'payment-method';

    columns = A([
        dtc.create({
            valuePath: 'code',
            name: this.intl.t('payment_method.attr.code')
        }),
        dtc.create({
            valuePath: 'name',
            name: this.intl.t('payment_method.attr.name')
        }),
        dtc.create({
            buttons: [
                {preset: 'edit'},
                {preset: 'delete'},
            ]
        }),
    ]);

    @action
    editPaymentMethodRecord(record)
    {
        this.isEditingPaymentMethod = true;
        this.paymentMethodSource = record;
    }

    @action
    deletePaymentMethodRecord(record)
    {
        this.qswal.confirmDelete(() => {
            KTApp.blockPage();
            record.destroyRecord().then(() => {
                KTApp.unblockPage();
                this.qswal.delete().s();
                this.set('refreshData', true);
            }).catch(() => {
                KTApp.unblockPage();
                this.qswal.delete().e();
            });
        });
    }

    @action
    addPaymentMethod()
    {
        this.paymentMethodSource = this.store.createRecord('payment-method');
        this.isEditingPaymentMethod = true;
    }

    @action
    savePaymentMethodForm(paymentMethod, event) {
        event.preventDefault();
        KTApp.blockPage();

        if (paymentMethod.isNew) {
            let qswal = this.qswal.create();
        } else {
            let qswal = this.qswal.edit();
        }

        return paymentMethod
            .save()
            .then(() => {
                KTApp.unblockPage();
                this.paymentMethodSource = null;
                this.isEditingPaymentMethod = false;
                this.refreshData = true;
                qswal.s();
            })
            .catch((response) => {
                KTApp.unblockPage();
                qswal.e(response.errors[0]);
            });
    }

}

