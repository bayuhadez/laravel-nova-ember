import Model, { attr, belongsTo } from '@ember-data/model';

export default class SalesReceiptServiceModel extends Model {

    @attr('number', {defaultValue: 0}) orderQuantity;
    @attr('number', {defaultValue: 0}) sellPrice;
    @attr('number', {defaultValue: 0}) total;

    @belongsTo('service') service;
    @belongsTo('sales-order-service') salesOrderService;
    @belongsTo('sales-receipt') salesReceipt;

}
