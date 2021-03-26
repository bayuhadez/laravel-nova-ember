import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default Route.extend({
	model(params)
	{
		return hash({
			type: params.id,
		});
	},

	setupController(controller, model) {
		controller.setProperties({
			type: model.type,
		});
	}
});
