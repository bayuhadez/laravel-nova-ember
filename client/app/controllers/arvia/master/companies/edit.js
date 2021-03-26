import Controller from '@ember/controller';
import { isBlank } from '@ember/utils';
import { action, computed } from '@ember/object';
import { tracked } from '@glimmer/tracking';
import { inject as service } from '@ember/service';
import dtc from 'rcf/utils/dtcolumn';
import lookupValidator from 'ember-changeset-validations';
import Changeset from 'ember-changeset';
import MCompanyValidations from 'rcf/validations/m-company';

export default class AxxMasterCompaniesEditController extends Controller {

    MCompanyValidations = MCompanyValidations;

    @service intl;
    @service currentUser;
    @tracked showTabContent1 = true;
    @tracked showTabContent3 = false;
    //Edit child cabang
    isEditingChildCompany = false;
    @tracked disabledParentCompanyInput = false;
    //Cabang
    @tracked showErrors = false;
    @tracked refreshDtChildCompany = false;
    @tracked isEditingChildCompanyForm = false;
    @tracked formChildCompanyModel = null;
    @tracked formChildCompanyChangeset = null;
    //Address
    @tracked addressShowErrors = false;
    @tracked refreshDtAddress = false;
    @tracked isEditingAddress = false;
    @tracked formAddressModel = null;
    @tracked formCompanyAddressModel = null;
    @tracked formCompanyAddressChangeset = null;

    childCompanyIncludeParameters = 'warehouses,updated-by,company-addresses.address';

    @computed('company.id')
    get childCompanyFilterParameters()
    {
        return { childInCompany: this.company.id };
    }

    @computed('company.id')
    get companyAddressFilterParameters() {
        let filters = {};
        if (!isBlank(this.company.get('id'))) {
            filters.inCompany = this.company.get('id');
        } else {
            filters.inCompany = null;
        }
        return filters;
    }
    companyAddressIncludeParameters = "address,address.country,address.province,address.regency,company";

    //Columns
    childCompanyColumn = [
        dtc.create({
            name: this.intl.t('company.attr.code'),
            valuePath: 'code',
        }),
        dtc.create({
            name: this.intl.t('company.attr.name'),
            valuePath: 'name',
        }),
        dtc.create({
            name: this.intl.t('company.attr.division'),
            valuePath: 'divisionName',
        }),
        dtc.create({
            name: this.intl.t('company.attr.defaultAddress'),
            valuePath: 'defaultCompanyAddressName',
        }),
        dtc.create({
            name: this.intl.t('company.attr.valueAddedTaxNumber'),
            valuePath: 'valueAddedTaxNumber',
        }),
        dtc.create({
            name: this.intl.t('company.attr.valueAddedTaxType'),
            valuePath: 'valueAddedTaxTypeName',
        }),
        dtc.create({
            name: this.intl.t('company.attr.total_warehouses'),
            valuePath: 'warehouses.length',
            routeLink: 'master.warehouses'
        }),
        dtc.create({
            name: this.intl.t('company.attr.updatedAt'),
            valuePath: 'updatedAt',
        }),
        dtc.create({
            name: this.intl.t('company.rel.updatedBy'),
            valuePath: 'updatedBy.person.fullname',
        }),
        dtc.create({
            buttons: [
                {preset: 'edit'},
                {preset: 'delete'},
            ]
        }),
    ];

    addressColumn = [
        dtc.create({
            name: this.intl.t('address.attr.address'),
            valuePath: 'address.address',
        }),
        dtc.create({
            name: this.intl.t('address.rel.regency'),
            valuePath: 'address.regency.name',
        }),
        dtc.create({
            name: this.intl.t('address.rel.province'),
            valuePath: 'address.province.name',
        }),
        dtc.create({
            name: this.intl.t('address.attr.pobox'),
            valuePath: 'address.pobox',
        }),
        dtc.create({
            name: this.intl.t('address.attr.isDefault'),
            component: "input-checkbox",
            checked: 'isDefault',
            change: this.toggleAddressDefault,
        }),
        dtc.create({
            buttons: [
                {preset: 'edit'},
                {preset: 'delete'},
            ]
        }),
    ];
    //End columns

    //Navigation
    @action
    activateTabContent1()
    {
        this.showTabContent1 = true;
        this.showTabContent3 = false;
    }
    @action
    activateTabContent3()
    {
        this.showTabContent1 = false;
        this.showTabContent3 = true;
    }
    //End of navigation

    //Operations for address
    clearFormAddress()
    {
        this.formAddressModel = null;
        this.formCompanyAddressModel = null;
        this.formCompanyAddressChangeset = null;
        this.isEditingAddress = false;
        this.addressShowErrors = false;
        this.refreshDtAddress = true;
    }

    @action
    addAddress(addressName)
    {
        this.formAddressModel.address = addressName;
        return this.formAddressModel.save();
    }

    @action
    addCompanyAddress()
    {
        if (!isBlank(this.formCompanyAddressModel) || !isBlank(this.formCompanyAddressChangeset)) {
            this.formCompanyAddressModel.rollbackAttributes();
            this.formCompanyAddressChangeset.rollback();
        }

        if (!isBlank(this.formAddressModel)) {
            this.formAddressModel.rollbackAttributes();
        }

        let newAddress = this.store.createRecord('address');
        let companyAddress = this.store.createRecord("company-address", {
            company: this.company,
            address: newAddress
        });
        this.formCompanyAddressModel = companyAddress;
        this.formCompanyAddressChangeset = new Changeset(companyAddress);
        this.formAddressModel = newAddress;

        this.isEditingAddress = true;
    }

    @action
    editCompanyAddress(companyAddress)
    {
        if (!isBlank(this.formCompanyAddressModel)) {
            this.formCompanyAddressModel.rollbackAttributes();
        }

        if (!isBlank(this.formAddressModel)) {
            this.formAddressModel.rollbackAttributes();
        }

        this.formCompanyAddressModel = companyAddress;
        this.formCompanyAddressChangeset = new Changeset(companyAddress);
        companyAddress.get('address').then((address) => {
            this.formAddressModel = address;
        });

        this.isEditingAddress = true;
    }

    @action
    deleteCompanyAddress(companyAddress)
    {
        this.qswal.confirmDelete(() => {
            KTApp.blockPage();
            companyAddress.destroyRecord().then(() => {
                KTApp.unblockPage();
                this.qswal.delete().s();
                this.clearFormAddress();
            }).catch(() => {
                KTApp.unblockPage();
                this.qswal.delete().e();
            });
        });
    }

    @action
    setAddress(address)
    {
        this.formCompanyAddressChangeset.address = address;
        this.formAddressModel = address;
    }

    @action
    saveAddress()
    {
        if (!isBlank(this.formCompanyAddressModel)) {
            KTApp.blockPage();
            this.formAddressModel.save().then(() => {
                this.formCompanyAddressChangeset.save().then(() => {
                    this.formCompanyAddressModel.save().then((companyAddress) => {
                        companyAddress.isInTable = true;
                        if (companyAddress.isNew) {
                            this.qswal.create().s();
                        } else {
                            this.qswal.edit().s();
                        }
                        this.refreshDtAddress = true;
                        this.isEditingAddress = false;
                        this.formAddressModel = null;
                        this.formCompanyAddressModel = null;
                        this.formCompanyAddressChangeset = null;
                    });
                })
            }).finally(() => {
                KTApp.unblockPage();
                this.addressShowErrors = false;
            });
        }
    }

    @action
    cancelAddress()
    {
        if(this.formCompanyAddressChangeset) {
            this.formCompanyAddressChangeset.rollback();
        }
        this.formCompanyAddressModel.rollbackAttributes();
        this.formAddressModel.rollbackAttributes();
        this.formCompanyAddressModel = null;
        this.formCompanyAddressChangeset = null;
        this.isEditingAddress = false;
        this.refreshDtAddress = true;
    }

    @action
    toggleAddressDefault(companyAddressModel)
    {
        KTApp.blockPage();
        var previousDefaultRecord = null;
        companyAddressModel.isDefault = !companyAddressModel.isDefault;

        companyAddressModel.save().then((iam) => {

            // find previous addresses is_default to true and set it to false
            previousDefaultRecord = this.company.companyAddresses.filter((ca) => ca.isDefault && iam.id != ca.id).firstObject;

            if (!isBlank(previousDefaultRecord)) {
                previousDefaultRecord.isDefault = false;
                previousDefaultRecord.save().then(() => {
                    KTApp.unblockPage();
                    this.refreshDtAddress = true;
                });
            } else {
                KTApp.unblockPage();
                this.refreshDtAddress = true;
            }
        });
    }
    //End operations for address

    @action
    editChildCompany(record)
    {
        this.isEditingChildCompany = true;
        this.transitionToRoute('axx.master.companies.edit', record.id);
    }

    @action
    deleteRecord(record)
    {
        this.qswal.confirmDelete(() => {
            KTApp.blockPage();
            record.destroyRecord().then(() => {
                KTApp.unblockPage();
                this.qswal.delete().s();
            }).catch((err) => {
                KTApp.unblockPage();
                this.qswal.delete().e();
            });
        });
    }

    @action
    async saveCompany(changeset, event)
    {
        changeset.updatedBy = this.currentUser.user;

        event.preventDefault();

        await changeset.validate();

        if (changeset.isValid) {
            KTApp.blockPage();
            changeset.save().then(() => {
                KTApp.unblockPage();
                this.qswal.edit().s();
            }).catch((err) => {
                KTApp.unblockPage();
                this.showErrors = true;
                this.qswal.edit().e();
            });
        } else {
            this.showErrors = true;
        }
    }

    @action
    createChildCompany()
    {
        let childCompany = this.store.createRecord('company', {
            parentCompany: this.company
        });
        this.formChildCompanyModel = childCompany;
        this.formChildCompanyChangeset = new Changeset(childCompany, lookupValidator(MCompanyValidations), MCompanyValidations);
        this.isEditingChildCompanyForm = true;
    }

    @action
    closeChildCompanyModal()
    {
        this.formChildCompanyChangeset.rollback();
        this.formChildCompanyModel.rollbackAttributes();
        this.isEditingChildCompanyForm = false;
    }

    @action
    async saveChildCompany(changeset, event)
    {
        event.preventDefault();

        await changeset.validate();

        if (changeset.isValid) {
            KTApp.blockPage();
            changeset.save().then(() => {
                KTApp.unblockPage();
                this.isEditingChildCompanyForm = false;
                this.refreshDtChildCompany = true;
                this.qswal.create().s();
            }).catch((err) => {
                KTApp.unblockPage();
                this.showErrors = true;
                this.qswal.create().e();
            });
        } else {
            this.showErrors = true;
        }
    }
}
