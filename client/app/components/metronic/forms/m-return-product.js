import Component from '@glimmer/component';
import { action } from '@ember/object';
import { isBlank } from '@ember/utils';
import { tracked } from '@glimmer/tracking';
import { inject as service } from '@ember/service';
import Promise, { resolve } from 'rsvp';
import { A } from '@ember/array';

export default class MetronicFormsMPrepareProductComponent extends Component {

    @service intl;
    @service store;
    @tracked productStocks = [];

    constructor()
    {
        super(...arguments);

        KTApp.blockPage();

        this.store.query('product-stock-movement', {
            filter: { product_salesorder_id: this.args.productSalesOrder.id },
            include: 'product-stock',
        }).then((psms) => {
            this.productStockMovements = psms;
            this.store.query('product-stock', {
                filter: { 'sortFifoByProductOut': this.args.productSalesOrder.get('product.id') },
                include: "product,stock-division,rack.warehouse"
            }).then((productStocks) => {
                this.productStocks = productStocks;
                
                this.productStocks.forEach((ps) => {
                    ps._tempInitialQuantity = ps.quantity;
    
                    let filteredPsm = A(this.productStockMovements.filter(psm => psm.get('productStock.id') === ps.id));

                    filteredPsm.forEach(psm => {
                        if (psm.inOrOut === 1) {
                            ps._tempInitialQuantity += psm.quantity;
                        } else if (psm.inOrOut === 0) {
                            ps._tempInitialQuantity -= psm.quantity;
                        }
                    });

                    let remainingQty = this.args.productSalesOrder.get('amountReturned');
                    // reset tempReturnedValue to 0
                    ps._tempReturnedAmount = 0;
        
                    // do fifo assign recommendation
                    if (remainingQty > 0) {
                        if (ps.availableSlot > remainingQty) {
                            ps._tempReturnedAmount = remainingQty;
                        } else if (ps.availableSlot < remainingQty) {
                            ps._tempReturnedAmount = ps.availableSlot;
                        }
                        remainingQty = remainingQty - ps.availableSlot;
                    }
                });
            }).then(() => {
                KTApp.unblockPage();
            });
        });
    }

    get returnTotal()
    {
        if (!isBlank(this.productStocks)) {
            return this.productStocks.reduce(function (summation, current) {
                if (summation) {
                    return +summation + +current.get('_tempReturnedAmount');
                } else {
                    return +current.get('_tempReturnedAmount');
                }
            });
        } else {
            return 0;
        }
    }

    get submitInvalid()
    {
        if (this.args.productSalesOrder.get('amountReturned') === this.returnTotal) {
            return false;
        }
        return true;
    }

    @action
    updateProductSalesOrder()
    {
        this.args.productSalesOrder.save();
    }

}
