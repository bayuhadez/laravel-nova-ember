import Route from '@ember/routing/route';
import { hash } from 'rsvp';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';

export default class AxxMasterCustomersAddRoute extends Route {

    @service currentUser;
    @service phoneService;
    @service ('repositories/company-repository') companyRepository;
    @service ('repositories/person-repository') personRepository;

    model()
    {
        let currentCompanyId = this.currentUser.currentCompany.id;

        return hash({
            customer: this.store.createRecord('customer'),
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
            people: model.people,
            phone: this.phoneService.createPhoneClass()
        });
    }

    @action
    willTransition()
    {
        this.controller._tempPersonOptions = null;
        this.controller.showErrors = false;
        return true;
    }
}
