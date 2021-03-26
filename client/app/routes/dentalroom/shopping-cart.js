import Route from '@ember/routing/route';
import { inject as service } from '@ember/service'

export default Route.extend({
	verificationEmailAlert: service(),

	afterModel(/* model, transition */)
	{
		// show verification email alert
		this.get('verificationEmailAlert').showIfNotVerified();
	},

});
