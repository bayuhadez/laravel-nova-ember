import Route from '@ember/routing/route';
//import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';
import RSVP from 'rsvp';
import { computed } from '@ember/object';

export default Route.extend({
	model(params) {
		let order = this.store.findRecord(
			'order',
			params.id,
			{include: 'order-details.product,voucher'}
		);

		return RSVP.hash({
			order: order,
		});
	},

	parentController: computed(function () {
		return this.controllerFor('application');
	}),

	setupController(controller, model)
	{
		this._super(...arguments);
		this.get('parentController').set('displayVerificationAlert', true);

		controller.setProperties({
			order: model.order,
			isProcessing: false,
		});
	}
});
