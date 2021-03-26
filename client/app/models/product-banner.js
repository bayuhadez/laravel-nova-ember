import DS from 'ember-data';
import ENV from 'rcf/config/environment';
import { computed } from '@ember/object'

export default DS.Model.extend({
	bannerImagePath: DS.attr('string'),
	bannerName: DS.attr('string'),

	product: DS.belongsTo(),

	imageUrl: computed('banner_image_path', function () {
		return this.get('bannerImagePath');
	}),
});
