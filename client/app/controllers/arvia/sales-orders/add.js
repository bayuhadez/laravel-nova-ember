import Controller from '@ember/controller';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';

export default class AxxSalesOrdersAddController extends Controller {
    @service('repositories/sales-order') salesOrderRepository; 

    @action
    save(e)
    {
        if (!isBlank(e)) {
            e.preventDefault();
        }

        this.salesOrderRepository.save(this.salesOrder).then(() => {
            this.qswal.create().s();
            // redirect to sales order list page
            this.transitionToRoute(this.r('sales-orders.index'));
        }).catch((err) => {
            alert(err);
        });
    }

    @action
    saveAsFinished(e)
    {
        this.salesOrder.status = 2;
        this.save(e);
    }
}
