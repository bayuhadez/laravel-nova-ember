import Route from '@ember/routing/route';
import { inject as service } from '@ember/service'
import { isBlank } from '@ember/utils'
//import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';

export default Route.extend({

	currentUser: service(),
	verificationEmailAlert: service(),

	beforeModel(transition)
	{
		if (isBlank(this.get('currentUser.user'))) {
			transition.abort();
		}
	},

	afterModel(model, transition) {
		// show verification email alert
		this.get('verificationEmailAlert').showIfNotVerified();
	}
});
