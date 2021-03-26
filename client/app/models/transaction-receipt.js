import Model, { belongsTo, hasMany } from '@ember-data/model';

export default class TransactionReceiptModel extends Model {
    // Relationships:
    @belongsTo('customer') customer;
    @belongsTo('supplier') supplier;
    @hasMany('product-transaction-receipt') productTransactionReceipts;
    @hasMany('service-transaction-receipt') serviceTransactionReceipts;
}
