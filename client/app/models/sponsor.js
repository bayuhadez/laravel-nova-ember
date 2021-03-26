import DS from 'ember-data';
import ENV from 'rcf/config/environment';
import { computed } from '@ember/object'

export default DS.Model.extend({
	sponsor_image_path: DS.attr('string'),
    sponsor_name: DS.attr('string'),
    platinum: DS.attr('boolean'),

    imageUrl: computed('sponsor_image_path', function () {
		return ENV.apiUrl + '/storage/' + this.get('sponsor_image_path');
	}),
});
