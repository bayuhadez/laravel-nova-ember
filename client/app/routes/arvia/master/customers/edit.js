import Route from '@ember/routing/route';
import { hash } from 'rsvp';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';

export default class AxxMasterCustomersEditRoute extends Route {

    @service currentUser;
    @service phoneService;
    @service ('repositories/company-repository') companyRepository;
    @service ('repositories/person-repository') personRepository;

    model(params)
    {
        let currentCompanyId = this.currentUser.currentCompany.id;

        return hash({
            customer: this.store.findRecord('customer', params.customer_id, {
                include: 'company,person,pic,parent-customer,children-customer'
            }),
            customers: this.store.findAll('customer', {
                include: 'company,person,pic,parent-customer,children-customer'
            }),
            currentUserCompanies: this.companyRepository.getCompaniesWithChildren(currentCompanyId),
            companies: this.companyRepository.getCompaniesUsedByCustomer(),
            people: this.personRepository.getAll()
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            customer: model.customer,
            customers: model.customers,
            companies: model.companies,
            people: model.people
        });

        let customer = model.customer;
        controller.set('companyCustomerSource', customer);

        customer.get('parentCustomer').then((parentCustomer) => {

            if (!isBlank(parentCustomer)) {
                customer = parentCustomer;
                controller.set('companyCustomerSource', parentCustomer);
            }

            let company = customer.get('company');
            let person = customer.get('person');

            hash({
                company: company,
                person: person,
            }).then((obj) => {

                let phone = this.phoneService.createPhoneClass();

                if (!isBlank(obj.company)) {
                    this.phoneService.copyPhoneAttributes(phone, obj.company);
                    controller.set('phone', phone);
                    controller.set('customerAddressSource', obj.company);
                } else if (!isBlank(obj.person)) {
                    this.phoneService.copyPhoneAttributes(phone, obj.person);
                    controller.set('phone', phone);
                    controller.set('customerAddressSource', obj.person);
                }
            });
        });
    }

    @action
    willTransition()
    {
        // reset all controller state
        this.controller.activeTab = "tab_address";
        this.controller.showCustomerErrors = false;
        this.controller._tempPersonOptions = null;

        // address
        this.controller.address = null;
        this.controller.customerAddressSource = null;
        this.controller.intermediateAddress = null;
        this.controller.isEditingAddress = false;
        this.controller.refreshAddressDt = false;
        this.controller.showAddressErrors = false;

        // company-customer

        this.controller.isEditingCompanyCustomer = false;
        this.controller.showCompanyCustomerErrors = false;
        this.controller.companyCustomer = null;
        this.controller.companyCustomerSource = null;
        this.controller.refreshCompanyCustomerDt = false;
        return true;
    }

}
