import { A } from '@ember/array';
import { computed, set } from '@ember/object';
import { isEmpty } from '@ember/utils';

// How to use :
// `import ProductMovementRef from 'rcf/utils/product-movement-reference';`
export default class ProductMovementReference {

    // Properties:
    product; // contains product
    quantity = 0;

    inProductStockMovements = A(); // contains product-stock-movement
    outProductStockMovements = A(); // contains product-stock-movement

    constructor (product, quantity)
    {
        set(this, 'product', product);
        set(this, 'quantity', quantity);
    }

    // computed properties:
    @computed(
        'inProductStockMovements.[].quantity',
        'quantity'
    )
    get remains()
    {
        let quantities = this
            .inProductStockMovements
            .map((productStockMovement) => {
                return parseFloat(productStockMovement.quantity);
            });

        let summation = 0;

        if (!isEmpty(quantities)) {
            summation = quantities.reduce((summation, current) => {
                return summation + current;
            });
        }

        return this.quantity - summation;
    }
}
