import DS from 'ember-data';
import ENV from 'rcf/config/environment';
import { computed } from '@ember/object'

export default DS.Model.extend({
	image: DS.attr('string'),

	/* Relationship */
	company: DS.belongsTo('company'),
	product: DS.belongsTo('product'),

	/* Computed Property */
	imageUrl: computed('image', function () {
		return this.get('image');
	}),
});
