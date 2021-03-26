import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import dtc from 'rcf/utils/dtcolumn';
import { action } from '@ember/object';
import { A } from '@ember/array';
import WalletValidator from 'rcf/validations/wallet';

export default class AxxMasterWalletIndexController extends Controller {

    WalletValidator = WalletValidator;

    // services
    @service store;
    @service intl;
    @service qswal;

    @tracked refreshData = false;
    @tracked isEditingWallet = false;
    @tracked isAssigningWallet = false;
    @tracked walletFormModel;
    @tracked assignWalletFormModel;
    @tracked showErrors = false;

    formRecordModel = 'wallet';

    includeParameters = "companies";

    columns = A([
        dtc.create({
            valuePath: 'code',
            name: this.intl.t('wallet.attr.code')
        }),
        dtc.create({
            valuePath: 'name',
            name: this.intl.t('wallet.attr.name')
        }),
        dtc.create({
            valuePath: 'paymentTypeLabel',
            name: this.intl.t('wallet.attr.payment_type')
        }),
        dtc.create({
            valuePath: 'displayPaymentMethods',
            name: this.intl.t('wallet.rel.payment_methods')
        }),
        dtc.create({
            buttons: [
                {
                    preset: "buttonAction",
                    class: 'btn btn-icon btn-circle',
                    icon: "flaticon-buildings",
                    onAction: this.openAssignCompanyWalletModal,
                },
                {preset: 'edit'},
                {preset: 'delete'},
            ]
        }),
    ]);

    @action
    openAssignCompanyWalletModal(wallet)
    {
        this.assignWalletFormModel = wallet;
        this.isAssigningWallet = true;
    }

    @action
    closeAssignCompanyWalletModal()
    {
        this.isAssigningWallet = false;
        this.assignWalletFormModel = null;
    }

    @action
    saveAssignCompanyWalletForm(wallet, event)
    {
        event.preventDefault();
        KTApp.blockPage();
        wallet.save().then(() => {
            KTApp.unblockPage();
            this.qswal.s();
        }).catch(e => {
            KTApp.unblockPage();
            this.qswal.e(e);
        })
    }

    @action
    editWalletRecord(record)
    {
        this.isEditingWallet = true;
        this.walletFormModel = record;
    }

    @action
    deleteWalletRecord(record)
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
    addWallet()
    {
        this.walletFormModel = this.store.createRecord('wallet');
        this.isEditingWallet = true;
    }

    @action
    async saveWalletForm(walletChangeset, event) {
        event.preventDefault();
        KTApp.blockPage();
        this.showErrors = true;

        if (this.walletFormModel.isNew) {
            var qswal = this.qswal.create();
        } else {
            var qswal = this.qswal.edit();
        }

        await walletChangeset.validate();

        if(walletChangeset.isValid) {
            try {
                await walletChangeset.save()
                KTApp.unblockPage();
                this.walletFormModel = null;
                this.isEditingWallet = false;
                this.refreshData = true;
                qswal.s();
            } catch (e) {
                KTApp.unblockPage();
                this.showErrors = true;
                qswal.e(e);
            }
        } else {
            KTApp.unblockPage();
            this.showErrors = true;
            qswal.e(walletChangeset.get('errors'));
        }
    }

}