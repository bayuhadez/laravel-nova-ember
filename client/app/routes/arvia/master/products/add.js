import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default class AxxMasterProductsAddRoute extends Route {
    model()
    {
        return hash({
            product: this.store.createRecord('product'),
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            product: model.product,
        });
    }

    willTransition()
    {
        this.controller.rollbackAttributes();
    }
}