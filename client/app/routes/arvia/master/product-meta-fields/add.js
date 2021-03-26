import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default class AxxMasterProductMetaFieldsAddRoute extends Route {
    model()
    {
        return hash({
            productMetaField: this.store.createRecord('product-meta-field'),
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            productMetaField: model.productMetaField,
        });
    }

    willTransition()
    {
        this.controller.rollbackAttributes();
    }
}