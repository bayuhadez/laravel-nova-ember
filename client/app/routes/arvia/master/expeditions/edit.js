import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default class AxxMasterExpeditionsEditRoute extends Route {
    model(params)
    {
        return hash({
            expedition: this.store.findRecord('expedition', params.expedition_id),
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            expedition: model.expedition,
        });
    }

    willTransition()
    {
        this.controller.rollbackAttributes();
    }
}