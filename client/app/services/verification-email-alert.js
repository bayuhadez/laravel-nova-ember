import Service from '@ember/service';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils'

export default Service.extend({

	currentUser: service(),

	isShown: false,

	showIfNotVerified() {
		if (
			!isBlank(this.get('currentUser.user')) &&
			!this.get('currentUser.user.isEmailVerified')
		) {
			this.set('isShown', true);
		}
	},

	hide() {
		this.set('isShown', false);
	},
});
