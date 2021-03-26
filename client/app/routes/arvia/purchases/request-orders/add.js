import Route from '@ember/routing/route';
import { hash } from 'rsvp';
import { action } from '@ember/object';

export default class AxxMasterRequestOrdersAddRoute extends Route {
    model()
    {
        return hash({
            requestOrder: this.store.createRecord('request-order'),
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            requestOrder: model.requestOrder,
        });
    }

    @action
    willTransition()
    {
        this.controller.requestOrder.rollbackAttributes();
        this.cleanRequestOrderProducts();
        return true;
    }

    // Destroy all unsaved RO products when transitioning away from add RO
    cleanRequestOrderProducts()
    {
        let self = this;
        this.store
            .peekAll('request-order-product')
            .filterBy('isNew', true)
            .forEach(function (requestOrderProduct) {
                self.controller.newRequestOrderProducts.removeObject(requestOrderProduct);
                requestOrderProduct.deleteRecord();
            });
    }
}