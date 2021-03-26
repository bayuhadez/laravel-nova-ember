import Controller from '@ember/controller';
import { action } from '@ember/object';

export default class AxxMasterProductsEditProductStockController extends Controller {
    @action
    onClose()
    {
        this.transitionToRoute(this.r('master.products.edit'));
    }
}
