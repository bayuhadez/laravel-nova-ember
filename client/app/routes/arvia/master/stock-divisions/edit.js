import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default class AxxMasterStockDivisionsEditRoute extends Route {
    model(params)
    {
        return hash({
            company: this.store.findRecord('company', params.company_id),
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            company: model.company,
        });
    }

    willTransition()
    {
        this.controller.rollbackAttributes();
    }
}