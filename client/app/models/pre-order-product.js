import DS from 'ember-data';
import { inject as service } from '@ember/service';
import { computed } from '@ember/object';

export default DS.Model.extend({
    // services
    numberFormatter: service(),

    sortingNumber: DS.attr(),
    quantity: DS.attr('number', {defaultValue: 1}),
    purchasePrice: DS.attr('number', {defaultValue: 0}),
    purchasePricePerPcs: DS.attr('number', {defaultValue: 0}),
    purchasePriceForeign: DS.attr('number', {defaultValue: 0}),
    purchasePriceForeignPerPcs: DS.attr('number', {defaultValue: 0}),
    discounts: DS.attr('number', {defaultValue: 0}),
    cost: DS.attr('number', {defaultValue: 0}),
    subTotal: DS.attr('number', {defaultValue: 0}),

    // relationships
    preOrder: DS.belongsTo('pre-order'),
    product: DS.belongsTo('product'),
    unit: DS.belongsTo('unit'),
    productUnit: DS.belongsTo('product-unit'),

    /**--- computed ---**/
    formattedPurchasePrice: computed('purchasePrice', function () {
        return this.numberFormatter.formatCurrency(this.get('purchasePrice'));
    }),

    formattedPurchasePricePerPcs: computed('purchasePricePerPcs', function () {
        return this.numberFormatter.formatCurrency(this.get('purchasePricePerPcs'));
    }),

    formattedSubTotal: computed('subTotal', function () {
        return this.numberFormatter.formatCurrency(this.get('subTotal'));
    }),

    formattedPurchasePriceForeign: computed('purchasePriceForeign', function () {
        return this.numberFormatter.formatDecimal(this.get('purchasePriceForeign'), {
            style: 'decimal',
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });
    }),

    formattedPurchasePriceForeignPerPcs: computed('purchasePriceForeignPerPcs', function () {
        return this.numberFormatter.formatDecimal(this.get('purchasePriceForeignPerPcs'), {
            style: 'decimal',
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });
    }),

});
