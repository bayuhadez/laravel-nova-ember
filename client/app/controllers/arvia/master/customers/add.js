import Controller from '@ember/controller';
import CustomerValidator from 'rcf/validations/m-customer';
import PhoneValidator from 'rcf/validations/phone';
import { tracked } from '@glimmer/tracking';
import { action } from '@ember/object';
import { isBlank } from '@ember/utils';
import { inject as service } from '@ember/service';

export default class AxxMasterCustomersAddController extends Controller {

    CustomerValidator = CustomerValidator;
    PhoneValidator = PhoneValidator;

    @tracked showErrors = false;
    @tracked _tempPersonOptions = null;

    @service phoneService;
    @service intl;

    breadcrumbs = {
        title: this.intl.t('customer.identifier'),
        route: "axx.master.customers",
        subNav: [
            {
                name: this.intl.t('customer.heading.add'),
            }
        ],
    };

    // Customer Form [
    get customerOptions()
    {
        return this.customers.filter((customer) => !customer.isNew && !customer.isSubCustomer);
    }

    get companyOptions()
    {
        return this.companies.filter((company) => !company.isNew);
    }

    get personOptions()
    {
        if (!isBlank(this._tempPersonOptions)) {
            return this._tempPersonOptions;
        } else {
            return this.people.filter((person) => !person.isNew);
        }
    }

    @action
    async updatePersonOptions(options)
    {
        this._tempPersonOptions = options;
    }

    @action
    async saveCustomerForm([customerChangeset, phoneChangeset], event)
    {
        event.preventDefault();
        KTApp.blockPage();

        // clear existing errors
        this.showErrors = false;

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
                this.transitionToRoute('axx.master.customers.edit', customer.get('id'));
                this.qswal.create().s();
            } catch (e) {
                KTApp.unblockPage();
                this.showErrors = true;
                this.qswal.create().e();
            }

        } else {
            KTApp.unblockPage();
            this.showErrors = true;
        }
    }
    // ]
}
