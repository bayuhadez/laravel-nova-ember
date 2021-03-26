import Model, { attr, belongsTo, hasMany } from '@ember-data/model';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';

export default class ProductSalesOrderModel extends Model {

    @service intl;
    @service moneyService;

    @attr('number') discount;
    @attr('number') orderQuantity;
    @attr('number') sellPrice;
    @attr('number') status;
    @attr('number', {defaultValue: 0}) amountApproved;
    @attr('number', {defaultValue: 0}) amountRejected;
    @attr('number', {defaultValue: 0}) amountPrepared;
    @attr('number', {defaultValue: 0}) amountReturned;
    @attr('number', {defaultValue: 0}) amountLeftToPrepare;
    @attr statusText;

    @belongsTo('product') product;
    @belongsTo('product-transaction-receipt') productTransactionReceipt;
    @belongsTo('sales-order') salesOrder;
    @belongsTo('stock-division') stockDivision;
    @hasMany('product-stock-movement') productStockMovements;

    @computed('finalAmount', 'sellPrice', 'discount')
    get subTotal()
    {
        let subtotal = (((this.finalAmount ?? 0) * (this.sellPrice ?? 0)) - (this.discount ?? 0));

        if (isNaN(subtotal)) {
            subtotal = 0;
        }

        return subtotal;
    }

    @computed('orderQuantity', 'amountRejected', 'amountLeftToApprove', 'amountLeftToPrepare', 'amountReturned')
    get finalAmount()
    {
        return (
            (this.orderQuantity ?? 0)
            - (this.amountRejected ?? 0)
            - (this.amountLeftToApprove ?? 0)
            - (this.amountLeftToPrepare ?? 0)
            - (this.amountReturned ?? 0)
        );
    }

    @computed('orderQuantity', 'amountApproved', 'amountRejected')
    get amountLeftToApprove()
    {
       return (
            (this.orderQuantity ?? 0)
            - (this.amountApproved ?? 0)
            - (this.amountRejected ?? 0)
       );
    }

}
