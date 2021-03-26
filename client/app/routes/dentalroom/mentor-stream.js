import Route from '@ember/routing/route';
import { inject } from '@ember/service';
import { hash } from 'rsvp';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';

export default Route.extend(AuthenticatedRouteMixin, {
	ProductService: inject(),
	can: inject(),

	model(params)
	{
		let self = this;

		let streamKey = params.key;

		let product = this.get('ProductService').getProductByStreamKey(streamKey);

		let revealMultiplex = {
			secret: "1560055298452632234",
			id: "bee4b32e8efa7b77",
			url: "https://reveal-js-multiplex-ccjbegmaii.now.sh",
		};

		return hash({
			streamKey: streamKey,
			revealMultiplex: revealMultiplex,
			product: product
		});
	},

	afterModel(model) {
		var self = this;

		let authorized = this.get('can').can('broadcast product', model.product);

		return authorized.then(function(response) {
			if (response !== true) {
				self.transitionTo(self.r('products'));
			}
		});

	},

	setupController(controller, model)
	{
		controller.setProperties({
			streamKey: model.streamKey,
			product: model.product,
			revealMultiplex: model.revealMultiplex,
		});
	}
});
