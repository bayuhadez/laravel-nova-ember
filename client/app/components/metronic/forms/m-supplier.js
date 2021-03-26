import Component from '@ember/component';
import dtc from 'rcf/utils/dtcolumn';
import { action, computed, set} from '@ember/object';
import { alias, filter } from '@ember/object/computed';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { tracked } from '@glimmer/tracking';

export default class MetronicFormsMSupplierComponent extends Component {
    @service store;
    @service intl;
    @service qswal;

    @tracked addressShowErrors = false;
    @tracked formAddressModel = null;
    @tracked formIntermediateAddressModel = null;
    @tracked formPhoneModel = null;
    @tracked formAddressModel = null;
    @tracked isEditingAddress = false;
    @tracked refreshDt = false;

    constructor() {
        super(...arguments);
        this.setProperties({
            categories: this.store.findAll('supplier-category'),
            currencies: this.store.findAll('currency'),
            companies: this.companies,
        });
    }

    companyAddressIncludeParameters = "address,address.country,address.province,address.regency,company";
    //personAddressIncludeParameters = "address,address.country,address.province,address.regency,person";
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

    get companyOptions()
    {
        return this.companies.filter((company) => !company.isNew);
    }

    @alias('supplier.company.companyAddresses') companyAddresses;
    @alias('supplier.person.personAddresses') personAddresses;

    get showAddressSection()
    {
        // show section only if supplier record have company/person assigned
        return !isBlank(this.supplier.company.get('id'));
    }

    @computed('supplier.company.id', 'supplier.person.id')
    get intermediateAddressModelName()
    {
        if (!isBlank(this.supplier.company.get('id'))) {
            return "company-address";
        /*
        } else if (!isBlank(this.supplier.person.get('id'))) {
            return "person-address";
        */
        } else {
            return "";
        }
    }

    @computed('supplier.company.id')
    get companyAddressFilterParameters() {
        let filters = {};
        if (!isBlank(this.supplier.company.get('id'))) {
            filters.inCompany = this.supplier.company.get('id');
        } else {
            filters.inCompany = null;
        }
        return filters;
    }

    @computed('supplier.company.id')
    get selectedCompanyStaffs() {
        if (isBlank(this.get('supplier.company.id'))) {
            return [];
        }

        return this.store.query('staff', {
            filter: { inCompany: this.get('supplier.company.id')},
            include: 'person,company',
        });
    }


    @computed('selectedCompanyStaffs.[]')
    get picOptions() {
        this.staffs = this.selectedCompanyStaffs;
        if (isBlank(this.staffs)) {
            return [];
        }
        return this.staffs.mapBy('person');
    }

    @computed('supplier.company.id')
    get isCompanyAttrDisabled() {
        return isBlank(this.supplier.company.get('id'));
    }

    @action
    addSupplierCategory(name) {
        return this.get('store').createRecord('supplier-category', {
            name : name
        }).save();
    }

    /**
     * Handle m-select addItems action on person & pic select dropdown
     */
    @action
    addPic(fullname) {
        return new Promise((resolve) => {
            if (!isBlank(fullname)) {
                let person = this.store.createRecord('person');
                set(person, 'fullname', fullname);
                set(this.supplier, 'pic', person)

                resolve(person);
            } else {
                resolve();
            }
        });
    }

    @action
    addCompany(name)
    {
        return this.get('store').createRecord('company', {
            name : name
        }).save();
    }

    @action
    addAddress(addressName)
    {
        this.formAddressModel.address = addressName;
        return this.formAddressModel.save();
    }

    @action
    deleteAddress(intermediateAddress)
    {
        this.qswal.confirmDelete(() => {
            KTApp.blockPage();
            intermediateAddress.destroyRecord().then(() => {
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
        this.formIntermediateAddressModel.address = address;
        this.formAddressModel = address;
    }

    @action
    setRegency(regency)
    {
        this.formAddressModel.regency = regency;
    }

    @action
    setProvince(province)
    {
        this.formAddressModel.province = province;
    }

    @action
    editAddress(intermediateAddress)
    {
        if (!isBlank(this.formIntermediateAddressModel)) {
            this.formIntermediateAddressModel.rollbackAttributes();
        }

        if (!isBlank(this.formAddressModel)) {
            this.formAddressModel.rollbackAttributes();
        }

        if (isBlank(intermediateAddress)) {
            let newAddress = this.store.createRecord('address');
            if (this.intermediateAddressModelName == "company-address") {
                this.formIntermediateAddressModel = this.store.createRecord("company-address", {
                    company: this.supplier.company,
                    address: newAddress
                });
            /*
            } else if (this.intermediateAddressModelName == "person-address") {
                this.formIntermediateAddressModel = this.store.createRecord("person-address", {
                    person: this.supplier.person,
                    address: newAddress
                });
            */
            }
            this.formAddressModel = newAddress;
        } else {
            this.formIntermediateAddressModel = intermediateAddress;
            intermediateAddress.get('address').then((address) => {
                this.formAddressModel = address;
            });
        }
        this.isEditingAddress = true;
    }

    @action
    saveAddress()
    {
        if (!isBlank(this.formIntermediateAddressModel)) {
            KTApp.blockPage();
            this.formAddressModel.save().then(() => {
                this.formIntermediateAddressModel.save().then((intermediateAddress) => {
                    intermediateAddress.isInTable = true;
                    if (intermediateAddress.isNew) {
                        this.qswal.create().s();
                    } else {
                        this.qswal.edit().s();
                    }
                    this.refreshDt = true;
                    this.isEditingAddress = false;
                    this.formAddressModel = null;
                    this.formIntermediateAddressModel = null;
                });
            }).finally(() => {
                KTApp.unblockPage();
                this.addressShowErrors = false;
            });
        }
    }

    @action
    cancelAddress()
    {
        this.formIntermediateAddressModel.rollbackAttributes();
        this.formAddressModel.rollbackAttributes();
        this.isEditingAddress = false;
        this.refreshDt = true;
    }

    clearFormAddress()
    {
        this.formAddressModel = null;
        this.formIntermediateAddressModel = null;
        this.isEditingAddress = false;
        this.addressShowErrors = false;
        this.refreshData = true;
    }

    @action
    toggleAddressDefault(intermediateAddressModel)
    {
        KTApp.blockPage();
        var previousDefaultRecord = null;
        intermediateAddressModel.isDefault = !intermediateAddressModel.isDefault;

        intermediateAddressModel.save().then((iam) => {

            // find previous addresses is_default to true and set it to false
            if (this.intermediateAddressModelName == 'company-address') {
                previousDefaultRecord = this.companyAddresses.filter((ca) => ca.isDefault && iam.id != ca.id).firstObject;
            } else if (this.intermediateAddressModelName == 'person-address') {
                previousDefaultRecord = this.personAddresses.filter((pa) => pa.isDefault && iam.id != pa.id).firstObject;
            }

            if (!isBlank(previousDefaultRecord)) {
                previousDefaultRecord.isDefault = false;
                previousDefaultRecord.save().then(() => {
                    KTApp.unblockPage();
                    this.refreshDt = true;
                });
            } else {
                KTApp.unblockPage();
                this.refreshDt = true;
            }
        });
    }
}
