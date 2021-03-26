import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default class AxxMasterRequestOrdersEditRoute extends Route {
    model(params)
    {
        return hash({
            requestOrder: this.store.findRecord('request-order', params.request_order_id),
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            requestOrder: model.requestOrder,
        });
    }

    willTransition()
    {
        this.controller.rollbackAttributes();
    }
}