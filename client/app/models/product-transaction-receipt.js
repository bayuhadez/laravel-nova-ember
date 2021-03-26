import Model, { attr, belongsTo, hasMany } from '@ember-data/model';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { validator, buildValidations } from 'ember-cp-validations';

const Validations = buildValidations({
    product: [
        validator('presence', {
            presence: true,
            message: 'Product tidak boleh kosong'
        }),
    ],
    quantity: [
        validator('presence', {
            presence: true,
            message: 'Qty tidak boleh kosong'
        }),
        validator('number', {
            allowBlank: false,
            allowString: true,
            integer: true,
            gt: 0,
            message: 'Qty harus numerik dan lebih dari 0'
        }),
    ],
    price: [
        validator('number', {
            allowBlank: true,
            allowString: true,
            message: 'Harga Modal Rupiah harus numerik'
        }),
    ],
    foreignPrice: [
        validator('number', {
            allowBlank: true,
            allowString: true,
            message: 'Harga Modal Asing harus numerik'
        }),
    ],
    discounts: [
        validator('number', {
            allowBlank: true,
            allowString: true,
            message: 'Diskon harus numerik'
        }),
    ],
    cost: [
        validator('number', {
            allowBlank: true,
            allowString: true,
            message: 'Biaya harus numerik'
        }),
    ],
});

export default class ProductTransactionReceiptModel extends Model.extend(Validations) {
    @service intl;
    @service moneyService;
    @service numberFormatter;

    @attr('number', {defaultValue: 0}) quantity;
    @attr('number', {defaultValue: 0}) price;
    @attr('number', {defaultValue: 0}) pricePerPcs;
    @attr('string', {defaultValue: '0'}) discounts;
    @attr('number', {defaultValue: 0}) subTotal;
    @attr('number', {defaultValue: 0}) cost;
    @attr('number', {defaultValue: 0}) total;
    @attr createdAt;
    @attr('number', {defaultValue: 0}) foreignPrice;
    @attr('number', {defaultValue: 0}) foreignPricePerPcs;

    // Relationships:
    @belongsTo('pre-order-product') preOrderProduct;
    @belongsTo('product-sales-order') productSalesOrder;
    @belongsTo('product') product;
    @belongsTo('product-unit') productUnit;
    @belongsTo('transaction-receipt') transactionReceipt;
    @hasMany('product-stock-movement') productStockMovements;

    @computed('price')
    get formattedPrice() {
        return this.numberFormatter.formatCurrency(this.price);
    };

    @computed('pricePerPcs')
    get formattedPricePerPcs() {
        return this.numberFormatter.formatCurrency(this.pricePerPcs);
    };

    @computed('subTotal')
    get formattedSubTotal() {
        return this.numberFormatter.formatCurrency(this.subTotal);
    };

    @computed('cost')
    get formattedCost() {
        return this.numberFormatter.formatCurrency(this.cost);
    };

    @computed('total')
    get formattedTotal() {
        return this.numberFormatter.formatCurrency(this.total);
    };

    @computed('foreignPrice')
    get formattedForeignPrice() {
        return this.numberFormatter.formatDecimal(this.foreignPrice, {
            style: 'decimal',
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });
    }

    @computed('foreignPricePerPcs')
    get formattedForeignPricePerPcs() {
        return this.numberFormatter.formatDecimal(this.foreignPricePerPcs, {
            style: 'decimal',
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });
    }

    /** ----- methods ----- **/
    resetProductUnitAndUnit()
    {
        this.productUnit = null;
        this.unit = null;
    }

    setBasePrice()
    {
        if (isBlank(this.product)) {
            this.price = 0;
        } else {
            this.price = this.product.get('basePrice');
        }
    }

    assignProduct(product)
    {
        this.product = product;
        this.setBasePrice();
    }

    /**
     * Calculate & Set subTotal
     */
    calculateAndSetSubTotal()
    {
        let subTotal = 0;
        let price = this.price || 0;
        let quantity = this.quantity || 0;
        let discounts = this.discounts;

        subTotal = price * quantity;

        if (!isBlank(discounts)) {
            subTotal = this.moneyService.calculateDiscount(discounts, subTotal);
        }

        this.subTotal = (subTotal <= 0) ? 0 : subTotal;
    }

    /**
     * Calculate & Set total
     */
    calculateAndSetTotal() // subTotal, cost
    {
        let total = Number(this.cost || 0) + this.subTotal;

        this.total = (total <= 0) ? 0 : total;
    }

    /**
     * Calculate price
     * consider: productUnit
     */
    calculatePricePerPcs(price)
    {
        if (isBlank(this.productUnit)) {
            return 0;
        } else if (this.productUnit.get('isPrimary') || isBlank(this.productUnit.get('conversionRate'))) {
            return price;
        } else {
            return Number(price / this.productUnit.get('conversionRate'));
        }
    }

    /**
     * Calculate & Set pricePerPcs
     * consider: productUnit, price
     */
    calculateAndSetPricePerPcs(isPpn = false)
    {
        this.pricePerPcs = 
            this.calculatePricePerPcs(
                isPpn
                ? (this.price + (0.1 * this.price))
                : this.price
            );
    }

    /**
     * Calculate & Set foreignPricePerPcs
     * consider: productUnit, foreignPrice
     */
    calculateAndSetForeignPricePerPcs(isPpn = false)
    {
        this.foreignPricePerPcs = 
            this.calculatePricePerPcs(
                isPpn
                ? (Number(this.foreignPrice) + (0.1 * this.foreignPrice))
                : this.foreignPrice
            );
    }

    /**
     * Calculate & Set foreignPrice
     * consider: price, rate
     */
    calculateAndSetForeignPrice(currencyRate)
    {
        if (!isBlank(currencyRate) && currencyRate > 0) {
            this.foreignPrice = this.price / currencyRate;
        } else {
            this.foreignPrice = 0;
        }
    }

}
