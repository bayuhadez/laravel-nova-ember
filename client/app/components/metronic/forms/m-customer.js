import Component from '@glimmer/component';
import { inject as service } from '@ember/service';
import { action, computed } from '@ember/object';
import { isBlank } from '@ember/utils';
import { tracked } from '@glimmer/tracking';
import { alias, filter } from '@ember/object/computed';

export default class MetronicFormsMCustomerComponent extends Component {

    @service intl;
    @service store;
    @service qswal;
    @service('repositories/person-repository') personRepository;

    get disabledPhoneField()
    {
        return (isBlank(this.args.customer.company) && isBlank(this.args.customer.person));
    }

    get showTabSection()
    {
        // show section only if customer record have company/person assigned
        if (
            (!isBlank(this.args.customer.company) || !isBlank(this.args.customer.person))
            && (isBlank(this.args.customer.parentCustomer))
        ) {
            return true;
        }
        return false;
    }

    @action
    async addPerson(relation, fullname)
    {
        let firstName = fullname.split(' ').slice(0, 1).join(' ');
        let lastName = fullname.split(' ').slice(1).join(' ');
        // if name only one word use fullname as firstName
        if (firstName == '' && !isBlank(fullname)) {
            firstName = fullname;
        }

        let person = this.store.createRecord('person', {
            firstName: firstName,
            lastName: lastName
        });

        if (relation == 'person') {
            this.args.customer.person = person;
        } else if (relation == 'pic') {
            this.args.customer.pic = person;
        }

        let company = await this.args.customer.company;
        // if customer record has company assigned
        // create staff record with that person
        // and assign that new created staff into company
        if (!isBlank(company)) {
            return person.save().then((person) => {
                let staff = this.store.createRecord('staff', { person: person, company: company });
                return staff.save().then((staff) => {
                    return person;
                });
            });
        } else {
            return person.save();
        }
    }

    @action
    setPerson(person)
    {
        this.args.customer.person = person;

        if (!isBlank(person)) {

            if (isBlank(this.args.customer.company)) {
                this._updatePhoneRecord(person);
                if (!this.args.customer.data.isNew) {
                    // update address section source model
                    this.args.updateCustomerAddressSourceAction(person);
                }
            }
        }
    }

    @action
    setCompany(company)
    {
        this.args.customer.company = company;

        if (!isBlank(company)) {
            this._updatePhoneRecord(company);
            this._filterPersonOptionsByCompany(company);

            if (!this.args.customer.data.isNew) {
                // update address section source model
                this.args.updateCustomerAddressSourceAction(company);
            }
        }
    }

    @action
    addCompany(companyName)
    {
        let company = this.store.createRecord('company', { name: companyName });
        this._updatePhoneRecord(this.args.phone, company);
        this._filterPersonOptionsByCompany(company);
        return company.save();
    }

    @action
    async setParentCustomer(parentCustomer)
    {
        this.args.customer.parentCustomer = parentCustomer ?? null;

        if (!this.args.customer.data.isNew) {

            let companyCustomerSource = null;
            let customerAddressSource = null;
            let company = null;
            let person = null;

            if (this.args.customer.parentCustomer == null) {
                company = await this.args.customer.get('company');
                person = await this.args.customer.get('person');
                companyCustomerSource = this.args.customer.data;
            } else {
                company = await parentCustomer.get('company');
                person = await parentCustomer.get('person');
                companyCustomerSource = parentCustomer;
            }

            if (company) {
                customerAddressSource = company;
            } else if (person) {
                customerAddressSource = person;
            }

            this.args.updateCustomerAddressSourceAction(customerAddressSource);
            this.args.updateCompanyCustomerSourceAction(companyCustomerSource);
        }
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

    _updatePhoneRecord(model = null)
    {
        if (isBlank(model)) {
            this.args.phone.faxNumber = null;
            this.args.phone.mobileNumber = null;
            this.args.phone.telephoneNumber = null;
        } else {
            this.args.phone.faxNumber = model.get('faxNumber');
            this.args.phone.mobileNumber = model.get('mobileNumber');
            this.args.phone.telephoneNumber = model.get('telephoneNumber');
        }
    }

    _isInOptions(model, options)
    {
        return !isBlank(options.filterBy('id', model.get('id')));
    }

    _filterPersonOptionsByCompany(company)
    {
        let promise = this.personRepository.getPeopleInCompany(company.get('id'));

        promise.then((options) => {

            if (!isBlank(this.args.customer.person) && !this._isInOptions(this.args.customer.person, options)) {
                this.args.customer.person = null;
            }

            if (!isBlank(this.args.customer.pic) && !this._isInOptions(this.args.customer.pic, options)) {
                this.args.customer.pic = null;
            }

            this.args.updatePersonOptionsAction(options)
        });
    }

}
