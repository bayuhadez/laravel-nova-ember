import Route from '@ember/routing/route';
import RSVP from 'rsvp';

export default Route.extend({
	model()
	{
		let orders = this.store.findAll('order');

		return RSVP.hash({
			orders: orders
		});
	},

	setupController(controller, model)
	{
		controller.setProperties({
			orders: model.orders
		});
	}
});
