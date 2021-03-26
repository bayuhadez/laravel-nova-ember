import Model, { attr, belongsTo, hasMany } from '@ember-data/model';
import { inject as service } from '@ember/service';

export default class SalesOrderModel extends Model {
    @service intl;

    @attr('string') number;
    @attr('string') description;
    @attr('number', {defaultValue: 0}) discount;
    @attr('number', {defaultValue: 1}) status;
    @attr('number') grandTotal;
    @attr('boolean') requiresDelivery;
    @attr('date') createdAt;
    @attr('date') updatedAt;
    @attr('date', { defaultValue() { return new Date(); }}) orderedAt;

    @belongsTo('customer') customer;
    @belongsTo('company') company;
    @belongsTo('user') createdBy;
    @belongsTo('staff') warehouseStaff;
    @belongsTo('staff') sales;
    @hasMany('product-sales-order') productSalesOrders;
    @hasMany('sales-order-service') salesOrderServices;
    @belongsTo('customer', { inverse: null }) deliveryRecipientCustomer;
    @belongsTo('address') deliveryAddress;

    get formattedCreatedAt() {
        let date = this.createdAt;
        let format = 'DD/MM/YYYY';
        return moment(date).format(format);
    }

    get formattedUpdatedAt() {
        let date = this.updatedAt;
        let format = 'DD/MM/YYYY';
        return moment(date).format(format);
    }

    get formattedOrderedAt() {
        let date = this.orderedAt;
        let format = 'DD/MM/YYYY';
        return moment(date).format(format);
    }

    get statusLabel() {
        let statusText = '';

        switch (this.status) {
            case 0:
                statusText = 'Pending';
                break;
            case 1:
                statusText = 'Draft';
                break;
            case 2:
                statusText = 'Ready';
                break;
            case 3:
                statusText = 'In Process';
                break;
            case 4:
                statusText = 'Approved';
                break;
            case 5:
                statusText = 'On Hold';
                break;
        }

        return statusText;
    }

    get calculatedGrandTotal() {
        let discount = this.discount;
        let productTotal = this.calculateTotalProductSalesOrders;
        let serviceTotal = this.calculateTotalSalesOrderServices;
        let grandTotal = (productTotal + serviceTotal) - discount;
        this.grandTotal = grandTotal;
        return grandTotal;
    }

    get calculateTotalProductSalesOrders()
    {
        let total = 0;
        this.productSalesOrders.forEach((pso) => {
            total += pso.subTotal;
        });
        return total;
    }

    get calculateTotalSalesOrderServices()
    {
        let total = 0;
        this.salesOrderServices.forEach((sso) => {
            total += sso.total;
        });
        return total;
    }

    get requiresDeliveryText() {
        if (this.requiresDelivery) {
            return this.intl.t('yes');
        } else {
            return this.intl.t('no');
        }
    }
}
