import DS from 'ember-data';

export default DS.Model.extend({
	chats: DS.hasMany()
});
