import DS from 'ember-data';

export default DS.Model.extend({
	// attributes
	bank: DS.attr('string'),
	instructionPdfUrl: DS.attr('string'),
	orderNumber: DS.attr('string'),
	paymentType: DS.attr('string'),
	statusCode: DS.attr('string'),
	vaNumber: DS.attr('string'),
	createdAt: DS.attr('date'),
	updatedAt: DS.attr('date'),

	// relationships
	order: DS.belongsTo('order'),
});
