import Component from '@glimmer/component';
import { action } from '@ember/object';
import { isBlank } from '@ember/utils';
import { tracked } from '@glimmer/tracking';
import { inject as service } from '@ember/service';

export default class MetronicFormsMPrepareProductComponent extends Component {

    @service intl;
    @service store;
    @tracked productStocks = [];
    warehouseOptions;
    @tracked rackOptions;
    @tracked transitWarehouse;
    @tracked transitRack;

    constructor()
    {
        super(...arguments);

        KTApp.blockPage();

        this.warehouseOptions = this.store.findAll('warehouse');

        this.store.query('product-stock', {
            filter: { 'sortFifoByProduct': this.args.productSalesOrder.get('product.id') },
            include: "product,stock-division,rack.warehouse"
        }).then((productStocks) => {
            let remainingQty = this.args.productSalesOrder.get('amountPrepared');
            this.productStocks = productStocks;
            this.productStocks.forEach((ps) => {
                // reset tempTakenValue to 0
                ps._tempTakenAmount = 0;

                // do fifo assign recommendation
                if (remainingQty > 0) {
                    if (ps.quantity > remainingQty) {
                        ps._tempTakenAmount = remainingQty;
                    } else if (ps.quantity <= remainingQty) {
                        ps._tempTakenAmount = ps.quantity;
                    }
                    remainingQty = remainingQty - ps.quantity;
                }
            });

            KTApp.unblockPage();
        });
    }

    get prepareTotal()
    {
        if (!isBlank(this.productStocks)) {
            return this.productStocks.reduce(function (summation, current) {
                if (summation) {
                    return +summation + +current.get('_tempTakenAmount');
                } else {
                    return +current.get('_tempTakenAmount');
                }
            });
        } else {
            return 0;
        }
    }

    get submitInvalid()
    {
        if (this.args.productSalesOrder.get('amountPrepared') <= this.args.productSalesOrder.amountApproved) {
            return false;
        }
        return true;
    }

    @action
    updateProductSalesOrder()
    {
        this.args.productSalesOrder.save();
    }

    @action
    chooseWarehouse(warehouse)
    {
        this.transitWarehouse = warehouse;
        this.rackOptions = this.store.query('rack', {
            filter: { inWarehouse: warehouse.get('id') }
        });
    }
}
