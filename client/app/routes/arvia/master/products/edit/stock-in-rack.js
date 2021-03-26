import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default class AxxMasterProductsEditStockInRackRoute extends Route {

    model(params) {
        return hash({
            stockDivisionId: params.stock_division_id,
        });
    }
    
    setupController(controller, model)
    {
        let product = this.modelFor('axx.master.products.edit').product;
        controller.setProperties({
            productId: product.id,
            stockDivisionId: model.stockDivisionId,
        });
    }
}
