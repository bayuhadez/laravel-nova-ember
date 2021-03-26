import Model, {attr, belongsTo, hasMany} from '@ember-data/model';
import { isBlank } from '@ember/utils';

export default class ProductStockMovementModel extends Model {
    @attr('number') quantity;
    @attr('date') datetime;
    @attr('number') inOrOut;
    @attr('number') price;
    @attr('string') receiptNumber;
    @attr('string') from;
    @attr('string') to;
    @attr('number') stock;

    @belongsTo('customer') customer;
    @belongsTo('product') product;
    @belongsTo('rack') rack;
    @belongsTo('stock-division') stockDivision;
    @belongsTo('product-sales-order') productSalesOrder;
    @belongsTo('product-stock') productStock;
    @belongsTo('product-transaction-receipt') productTransactionReceipt;
    @belongsTo('user') user;
    @belongsTo('purchase-receipt') purchaseReceipt;
    @belongsTo('sales-receipt') salesReceipt;

    get formattedDatetime()
    {
        let date = this.datetime;
        let format = 'DD/MM/YYYY HH:mm';
        return moment(date).format(format);
    }

    get inOrOutType()
    {
        return this.inOrOut ? "In" : "Out";
    }
}
