import Route from '@ember/routing/route';
import RSVP from 'rsvp';

export default Route.extend({
	model(params)
	{
		let order = this.store.findRecord(
			'order',
			params.order_id,
			{include: 'transaction,order-details.product'}
		);

		return RSVP.hash({
			order: order,
		});
	},

	setupController(controller, model)
	{
		this._super(...arguments);
		controller.setProperties({
			order: model.order,
		});
	}

});
