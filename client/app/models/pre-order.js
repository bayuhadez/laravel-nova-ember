import DS from 'ember-data';
import PpnableMixin from 'rcf/mixins/ppnable';
import RoundableMixin from 'rcf/mixins/roundable';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default DS.Model.extend(PpnableMixin, RoundableMixin, {
    // services
    intl: service(),
    numberFormatter: service(),

    // attributes
    number: DS.attr('string'),
    sonNumber: DS.attr('string'),
    orderedAt: DS.attr('date'),
    dueAt: DS.attr('date'),
    currencyRate: DS.attr('number'),
    isPpn: DS.attr('boolean', {defaultValue: false}),
    status: DS.attr('number'),
    discounts: DS.attr(),
    total: DS.attr('number', {defaultValue: 0}),
    grandTotal: DS.attr('number', {defaultValue: 0}),
    roundedTotal: DS.attr('number', {defaultValue: 0}),
    createdAt: DS.attr('date'),
    updatedAt: DS.attr('date'),
    roundingType: DS.attr(),
    roundingValue: DS.attr(),

    // relationships
    company: DS.belongsTo('company'),
    createdBy: DS.belongsTo('user'),
    currency: DS.belongsTo('currency'),
    supplier: DS.belongsTo('supplier'),
    preOrderProducts: DS.hasMany('pre-order-product'),
    requestOrders: DS.hasMany('request-order'),

    // computed properties
    formattedCreatedAt: computed('createdAt', function() {
        return moment(this.get('createdAt')).format('DD/MM/YYYY');
    }),

    formattedOrderedAt: computed('orderedAt', function() {
        return moment(this.get('orderedAt')).format('DD/MM/YYYY');
    }),

    formattedDueAt:computed('orderedAt', function() {
        return moment(this.get('orderedAt')).format('DD/MM/YYYY');
    }),

    statusLabel: computed('status', function () {
        let statusText = '';
        switch (this.get('status')) {
            case 0: return this.intl.t('pre_order.status.pending');
            case 1: return this.intl.t('pre_order.status.done');
            case 2: return this.intl.t('pre_order.status.draft');
            default: return "";
        }
    }),

    calculatedTotal: computed('preOrderProducts.[]', function() {
        let total = 0;
        let preOrderProducts = this.get('preOrderProducts');
        preOrderProducts.forEach((preOrderProduct) => {
            total += preOrderProduct.get('subTotal');
        });
        return total;
    }),

    calculatedGrandTotal: computed('calculatedTotal', 'discounts', 'isPpn', function() {
        let grandTotal = this.get('calculatedTotal');
        if (this.get('isPpn')) {
            grandTotal += this.calculatePpnAmount(grandTotal);
        }
        return grandTotal;
    }),

    formattedCalculatedTotal: computed('calculatedTotal', function () {
        return this.numberFormatter.formatCurrency(this.get('calculatedTotal'));
    }),

    formattedCalculatedGrandTotal: computed('calculatedGrandTotal', function () {
        return this.numberFormatter.formatCurrency(this.get('calculatedGrandTotal'));
    }),

});
