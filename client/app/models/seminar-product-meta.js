import DS from 'ember-data';

export default DS.Model.extend({
	isSessionInProgress: DS.attr('boolean'),
	streamKey: DS.attr('string'),
	startTime: DS.attr('date'),
	playbackVideoUrl: DS.attr('string'),

    product: DS.belongsTo(),
    seminarProductSponsors: DS.hasMany(),
	speaker: DS.belongsTo('user'),
});
