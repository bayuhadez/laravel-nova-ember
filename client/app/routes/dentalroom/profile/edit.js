import Route from '@ember/routing/route';
import { inject as service } from '@ember/service'
import { isBlank } from '@ember/utils'
import { hash } from 'rsvp'

export default Route.extend({
	currentUser: service(),

	model(params) {

		let self = this;
		let user = self.store.findRecord(
			'user',
			params.user_id,
			{
                include: 'person',
				reload: true
            }
		);

		return hash({
			user: user
		});
	},

	setupController(controller, model)
	{
		this._super(...arguments);

		controller.setProperties({
			user: model.user,
			person: model.user.get('person'),
			isProcessing: false,
		});
	}
});
