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

		let chatRoom = product.then(function (product) {
			return product.get('chatRooms').then(function (chatRooms) {
				return chatRooms.get('firstObject');
			});
		});

		return hash({
			streamKey: streamKey,
			product: product,
			chatRoom: chatRoom,
		});
	},

	afterModel(model) {
		var self = this;

		let authorized = this.get('can').can('stream product', model.product);

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
			chatRoom: model.chatRoom,
		});
	}
});
