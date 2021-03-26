import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default class AxxMasterProductsEditRoute extends Route {
    model(params)
    {
        return hash({
            product: this.store.findRecord('product', params.product_id, {
                include: 'product-units,product-units.unit,company-products,expedition-products,product-meta-values'
            }),
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