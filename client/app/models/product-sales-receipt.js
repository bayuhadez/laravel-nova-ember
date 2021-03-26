import Model, { attr, belongsTo, hasMany } from '@ember-data/model';

export default class ProductSalesReceiptModel extends Model {

    @attr('number', {defaultValue: 0}) orderQuantity;
    @attr('number', {defaultValue: 0}) sellPrice;
    @attr('number', {defaultValue: 0}) total;

    @belongsTo('product') product;
    @belongsTo('product-sales-order') productSalesOrder;
    @belongsTo('sales-receipt') salesReceipt;

}
