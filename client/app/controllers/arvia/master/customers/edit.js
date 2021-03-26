import Controller from '@ember/controller';
import CustomerValidator from 'rcf/validations/m-customer';
import PhoneValidator from 'rcf/validations/phone';
import AddressValidator from 'rcf/validations/address';
import CompanyCustomerValidator from 'rcf/validations/m-company-customer';
import dtc from 'rcf/utils/dtcolumn';
import { tracked } from '@glimmer/tracking';
import { action, computed } from '@ember/object';
import { isBlank } from '@ember/utils';
import { inject as service } from '@ember/service';
import { hash } from 'rsvp';

export default class AxxMasterCustomersEditController extends Controller {

    CustomerValidator = CustomerValidator;
    PhoneValidator = PhoneValidator;
    AddressValidator = AddressValidator;
    CompanyCustomerValidator = CompanyCustomerValidator;

    @service intl;
    @service phoneService;

    @tracked activeTab = "tab_address";
    @tracked showCustomerErrors = false;
    @tracked _tempPersonOptions = null;

    breadcrumbs = {
        title: this.intl.t('customer.identifier'),
        route: "axx.master.customers",
        subNav: [
            {
                name: this.intl.t('customer.heading.edit'),
            }
        ],
    };

    // Customer Form [
    get customerOptions()
    {
        return this.customers.filter((customer) => !customer.isNew && !customer.isSubCustomer && customer != this.customer);
    }

    get companyOptions()
    {
        return this.companies.filter((company) => !company.isNew);
    }

    get personOptions()
    {
        if (this._tempPersonOptions == null) {
            return this.people.filter((person) => !person.isNew);
        } else {
            return this._tempPersonOptions;
        }
    }

    @action
    updatePersonOptions(options)
    {
        this._tempPersonOptions = options;
    }

    @action
    async saveCustomerForm([customerChangeset, phoneChangeset], event)
    {
        event.preventDefault();
        KTApp.blockPage();

        // clear existing errors
        this.showCustomerErrors = false;

        await customerChangeset.validate();
        await phoneChangeset.validate();

        if (customerChangeset.isValid && phoneChangeset.isValid) {

            try {
                let customer = await customerChangeset.save();
                let company = await customer.get('company');
                let person = await customer.get('person');

                // save phone model [
                phoneChangeset.execute();

                if (!isBlank(company)) {
                    this.phoneService.copyPhoneAttributes(company, this.phone);
                    await company.save();
                } else if (!isBlank(person)) {
                    this.phoneService.copyPhoneAttributes(person, this.phone);
                    await person.save();
                }
                // ]

                KTApp.unblockPage();
                this.qswal.edit().s();
            } catch (e) {
                KTApp.unblockPage();
                this.showCustomerErrors = true;
                this.qswal.edit().e();
            }

        } else {
            KTApp.unblockPage();
            this.showCustomerErrors = true;
        }
    }
    // ]

    // Address [

    @tracked address = null;
    // used for pointing address source records, value can be person/company record
    @tracked customerAddressSource = null;
    @tracked intermediateAddress = null;
    @tracked isEditingAddress = false;
    @tracked refreshAddressDt = false;
    @tracked showAddressErrors = false;

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

    companyAddressIncludeParameters = "address.country,address.province,address.regency,company";
    personAddressIncludeParameters = "address.country,address.province,address.regency,person";

    get companyAddressFilterParameters() {
        let filters = {};
        if (
            !isBlank(this.customerAddressSource)
            && this.customerAddressSource.constructor.modelName == 'company'
        ) {
            filters.inCompany = this.customerAddressSource.get('id');
        } else {
            filters.inCompany = null;
        }
        return filters;
    }

    get personAddressFilterParameters() {
        let filters = {};
        if (
            !isBlank(this.customerAddressSource)
            && this.customerAddressSource.constructor.modelName == 'person'
        ) {
            filters.inPerson = this.customerAddressSource.get('id');
        } else {
            filters.inPerson = null;
        }
        return filters;
    }

    @action
    addAddress()
    {
        this.address = this.store.createRecord('address');

        if (this.customerAddressSource.constructor.modelName == 'company') {
            this.intermediateAddress = this.store.createRecord('company-address', {
                address: this.address,
                company: this.customerAddressSource
            });
        } else if (this.customerAddressSource.constructor.modelName == 'person') {
            this.intermediateAddress = this.store.createRecord('person-address', {
                address: this.address,
                person: this.customerAddressSource
            });
        }

        this.isEditingAddress = true;
    }

    @action
    editAddress(intermediateAddress)
    {
        this.intermediateAddress = intermediateAddress;
        this.intermediateAddress.get('address').then((address) => {
            this.address = address;
            this.isEditingAddress = true;
        });
    }

    @action
    deleteAddress(intermediateAddress)
    {
        this.qswal.confirmDelete(() => {
            KTApp.blockPage();
            intermediateAddress.destroyRecord().then(() => {
                KTApp.unblockPage();
                this.qswal.delete().s();
                this.showAddressErrors = false;
                this.refreshAddressDt = true;
            }).catch(() => {
                KTApp.unblockPage();
                this.showAddressErrors = true;
                this.refreshAddressDt = true;
                this.qswal.delete().e();
            });
        });
    }

    @action
    async saveAddressForm(address, event)
    {
        event.preventDefault();
        KTApp.blockPage();
        this.showAddressErrors = false;

        await address.validate();

        if (address.isValid) {
            // save address
            address.save().then((address) => {
                // attach address into customer company/person
                this.intermediateAddress.save().then(() => {
                    KTApp.unblockPage();
                    this.qswal.edit().s();
                    this.refreshAddressDt = true;
                    this._clearAddressForm();
                });
            }).catch(() => {
                KTApp.unblockPage();
                this.showAddressErrors = true;
                this.qswal.edit().e();
            });
        } else {
            KTApp.unblockPage();
            this.showAddressErrors = true;
        }
    }
    
    @action
    cancelAddressForm(address, event)
    {
        event.preventDefault();
        address.rollback();
        this._clearAddressForm();
    }

    @action
    updateAddressModel(address)
    {
        this.address = address;
        this.intermediateAddress.address = address;
    }

    @action
    updateCustomerAddressSource(model)
    {
        this.customerAddressSource = model;
        this.isEditingAddress = false;
    }

    @action
    updateCompanyCustomerSource(model)
    {
        this.companyCustomerSource = model;
        this.isEditingCompanyCustomer = false;
    }

    @action
    toggleAddressDefault(intermediateAddress)
    {
        KTApp.blockPage();

        var prevDefaultRecord = null;
        intermediateAddress.isDefault = !intermediateAddress.isDefault;
        intermediateAddress.save().then((iam) => {
            // find previous addresses is_default to true and set it to false
            if (this.customerAddressSource.constructor.modelName == 'company') {
                prevDefaultRecord = this.customerAddressSource.companyAddresses.filter((ca) => ca.isDefault && iam.id != ca.id).firstObject;
            } else if (this.customerAddressSource.constructor.modelName == 'person') {
                prevDefaultRecord = this.customerAddressSource.personAddresses.filter((pa) => pa.isDefault && iam.id != pa.id).firstObject;
            }

            if (!isBlank(prevDefaultRecord)) {
                prevDefaultRecord.isDefault = false;
                prevDefaultRecord.save().then(() => {
                    KTApp.unblockPage();
                    this.refreshAddressDtDt = true;
                });
            } else {
                KTApp.unblockPage();
                this.refreshAddressDt = true;
            }
        });
    }

    _clearAddressForm()
    {
        this.showAddressErrors = false;
        this.isEditingAddress = false;
        this.intermediateAddress = null;
        this.address = null;
    }
    // ]


    // Company Customer [
    @tracked isEditingCompanyCustomer = false;
    @tracked showCompanyCustomerErrors = false;
    @tracked companyCustomer = null;
    // used for determine customer record to load in datatable company-customer, load parent customer record if sub customer
    @tracked companyCustomerSource = null;
    @tracked refreshCompanyCustomerDt = false;

    get companyCustomerFilterParameters()
    {
        return { inCustomer: this.companyCustomerSource.get('id') ?? null };
    }

    companyCustomerIncludeParameters = "company,customer,staffs.person";
    companyCustomerColumn = [
        dtc.create({
            name: this.intl.t('company.identifier'),
            valuePath: 'company.name',
        }),
        dtc.create({
            name: this.intl.t('company_customer.attr.credit_limit'),
            valuePath: 'creditLimit',
        }),
        dtc.create({
            name: this.intl.t('company_customer.attr.term_of_payment'),
            valuePath: 'termOfPayment',
        }),
        dtc.create({
            name: this.intl.t('company_customer.rel.staffs'),
            valuePath: 'staffsName',
        }),
        dtc.create({
            name: this.intl.t('company_customer.attr.ppn'),
            valuePath: 'ppnName',
        }),
        dtc.create({
            buttons: [
                {preset: 'edit'},
                {preset: 'delete'},
            ]
        }),
    ];

    @action
    addCompanyCustomer()
    {
        this.companyCustomer = this.store.createRecord('company-customer', { customer: this.companyCustomerSource });
        this.isEditingCompanyCustomer = true;
    }

    @action
    editCompanyCustomer(companyCustomer)
    {
        this.companyCustomer = companyCustomer;
        this.isEditingCompanyCustomer = true;
    }

    @action
    deleteCompanyCustomer(companyCustomer)
    {
        this.qswal.confirmDelete(() => {
            KTApp.blockPage();
            companyCustomer.destroyRecord().then(() => {
                KTApp.unblockPage();
                this.qswal.delete().s();
            }).catch(() => {
                KTApp.unblockPage();
                this.qswal.delete().e();
            });
        });
    }

    @action
    async saveCompanyCustomerForm(changeset, event)
    {
        event.preventDefault();
        KTApp.blockPage();

        this.showCompanyCustomerErrors = false;

        await changeset.validate();

        if (changeset.isValid) {
            changeset.save().then(() => {
                KTApp.unblockPage();
                this.qswal.edit().s();
                this.companyCustomer = null;
                this.isEditingCompanyCustomer = false;
                this.refreshCompanyCustomerDt = true;
            });
        } else {
            KTApp.unblockPage();
            this.showCompanyCustomerErrors = true;
        }
    }

    @action
    cancelCompanyCustomerForm(companyCustomer, event)
    {
        event.preventDefault();
        companyCustomer.rollback();
        this.companyCustomer = null;
        this.isEditingCompanyCustomer = false;
        this.showCompanyCustomerErrors = false;
    }
    // ]
}
