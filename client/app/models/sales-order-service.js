import Model, { attr, belongsTo } from '@ember-data/model';
import { computed } from '@ember/object';

export default class SalesOrderServiceModel extends Model {
    @attr('number', {defaultValue: 0}) discount;
    @attr('number', {defaultValue: 0}) orderQuantity;
    @attr('number', {defaultValue: 0}) sellPrice;

    @belongsTo('service') service;
    @belongsTo('sales-order') salesOrder;
    @belongsTo('service-transaction-receipt') serviceTransactionReceipt;

    @computed('orderQuantity', 'sellPrice', 'discount')
    get total()
    {
        return (
            (this.orderQuantity * this.sellPrice)
            - this.discount
        );
    }
}
