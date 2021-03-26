import Route from '@ember/routing/route';
import { inject as service } from '@ember/service'
import { isBlank } from '@ember/utils'

export default Route.extend({
	currentUser: service(),

	setupController(controller, model)
	{
		this._super(...arguments);

		controller.setProperties({
			user: this.get('currentUser.user'),
			isProcessing: false,
		});
	}
});
