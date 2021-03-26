import DS from 'ember-data';

export default DS.Model.extend({
	answer: DS.attr('string'),
	question: DS.attr('string'),
	sortingNumber: DS.attr('number'),
	createdAt: DS.attr('date'),
	updatedAt: DS.attr('date'),

	// relations
	createdBy: DS.belongsTo('user'),
});
