import DS from 'ember-data';

export default DS.Model.extend({
	message: DS.attr('string'),
	chatRoom: DS.belongsTo(),
	senderName: DS.attr('string'),
});
