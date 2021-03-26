import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default class AxxMasterProductMetaFieldsEditRoute extends Route {
    model(params)
    {
        return hash({
            productMetaField: this.store.findRecord('product-meta-field', params.product_meta_field_id),
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