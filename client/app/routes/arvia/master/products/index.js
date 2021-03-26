import Route from '@ember/routing/route';
import { inject as service } from '@ember/service';
import { hash } from 'rsvp';

export default class AxxMasterProductsIndexRoute extends Route {
    @service productService;
    
    model()
    {
        let lowStockCount = this
            .get('productService')
            .lowStockCount(this.get('model.id'))
            .then(function (response) {
                return response;
            });

        let outOfStockCount = this
            .get('productService')
            .outOfStockCount(this.get('model.id'))
            .then(function (response) {
                return response;
            });

        return hash({
            lowStockCount: lowStockCount,
            outOfStockCount: outOfStockCount,
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            lowStockCount: model.lowStockCount,
            outOfStockCount: model.outOfStockCount,
        });
    }
}