import Route from '@ember/routing/route';
import { hash } from 'rsvp';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';

export default class AxxMasterServicesAddRoute extends Route {

    @service currentUser;

    model()
    {
        return hash({
            service: this.store.createRecord('service'),
            serviceCategories: this.store.findAll('service-category'),
            companies: this.store.query('company', {
                filter: { weAndChildren: this.currentUser.getCompanyIds() }
            }),
        });
    }

    setupController(controller, model)
    {
        // prepare an empty company service for the new service
        model.service.companyServices.pushObject(this.store.createRecord('company-service'));

        controller.setProperties({
            service: model.service,
            serviceCategories: model.serviceCategories,
            companies: model.companies,
        });
    }

    @action
    async willTransition()
    {
        await this.controller.service.rollbackRelationships();
        this.controller.service.rollbackAttributes();
        return true;
    }
}
