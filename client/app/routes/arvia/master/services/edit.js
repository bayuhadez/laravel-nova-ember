import Route from '@ember/routing/route';
import { hash } from 'rsvp';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';

export default class AxxMasterServicesEditRoute extends Route {

    @service currentUser;

    model(p)
    {
        return hash({
            service: this.store.findRecord(
                'service',
                p.service_id,
                {
                    include: 'service-category,company-services.company,company-services.service',
                },
            ),
            serviceCategories: this.store.findAll('service-category'),
            companies: this.store.query('company', {
                filter: { weAndChildren: this.currentUser.getCompanyIds(), notUsedByService: p.service_id }
            }),
        });
    }

    setupController(controller, model)
    {
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
