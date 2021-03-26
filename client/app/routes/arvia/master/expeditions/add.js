import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default class AxxMasterExpeditionsAddRoute extends Route {
    model()
    {
        return hash({
            expedition: this.store.createRecord('expedition'),
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