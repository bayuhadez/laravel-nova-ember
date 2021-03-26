import DS from 'ember-data';
import { inject } from '@ember/service'
import { computed } from '@ember/object'

export default DS.Model.extend({
	numberFormatter: inject(),

	// attributes
	category: DS.attr('string'),
	name: DS.attr('string'),
	price: DS.attr('number'),
	quantity: DS.attr('number'),

	// relations
	order: DS.belongsTo('order'),
	product: DS.belongsTo('product'),

	// computed
	formattedPrice: computed('price', function () {
		return this.numberFormatter.formatCurrency(this.get('price'));
	}),
});
