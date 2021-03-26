import DS from 'ember-data';
import ENV from 'rcf/config/environment';
import { inject as service } from '@ember/service'
import { computed } from '@ember/object'

export default DS.Model.extend({

	numberFormatter: service(),

	name: DS.attr('string'),
	stock: DS.attr('number'),
	amount: DS.attr('number'),

	/* Relationship */
	company: DS.belongsTo('company'),

	/* Computed Properties */
	amountFormatted: computed('amount', function() {
		return this.numberFormatter.formatCurrency(this.get('amount'));
	}),

});
