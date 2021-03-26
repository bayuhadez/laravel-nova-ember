import Model, { attr, belongsTo } from '@ember-data/model';

export default class ServiceTransactionReceiptModel extends Model {

    @attr('number', {defaultValue: 0}) quantity;
    @attr('number', {defaultValue: 0}) price;
    @attr('number', {defaultValue: 0}) total;

    @belongsTo('service') service;
    @belongsTo('sales-order-service') salesOrderService;
    @belongsTo('transaction-receipt') transactionReceipt;

}
