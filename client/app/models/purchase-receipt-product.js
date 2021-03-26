import DS from 'ember-data';
import { inject } from '@ember/service'
import { computed } from '@ember/object'

export default DS.Model.extend({
	numberFormatter: inject(),

    // attributes
    quantity: DS.attr('number'),
    price: DS.attr('number'),
    discount: DS.attr('string'),
    subtotal: DS.attr('number'),

    // relationships
    bill: DS.belongsTo('bill'),
    company: DS.belongsTo('company'),
    location: DS.belongsTo('location'),
    product: DS.belongsTo('product'),
    rack: DS.belongsTo('rack'),

    // computed
    formattedPrice: computed('price', function () {
        return this.numberFormatter.formatCurrency(this.get('price'));
    }),

    formattedSubtotal: computed('subtotal', function () {
        return this.numberFormatter.formatCurrency(this.get('subtotal'));
    }),
});
