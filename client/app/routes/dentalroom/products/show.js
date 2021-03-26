import Route from '@ember/routing/route';
import { inject as service } from '@ember/service';
import { hash } from 'rsvp';
import { isBlank } from '@ember/utils';

export default Route.extend({
	productService: service(),
	currentUser: service(),
	can: service(),

	shoppingCart: service('shopping-cart'),

	model(params) {
		var self = this;
		let currentUser = this.get('currentUser.user');
		var canStream = false;

		let product = this.store.findRecord(
			'product',
			params.product_id,
			{
				include: 'product-banner'+
				',seminar-product-meta'+
				',seminar-product-meta.seminar-product-sponsors'+
				',seminar-product-meta.speaker'
            }
		);

		if (!isBlank(currentUser)) {
			canStream = product.then(function (product) {
				return product.get('seminarProductMeta').reload().then(function (seminarProductMeta) {
					return self.get('can').can('stream product', product);
				});
			});
		}

		return hash({
			currentUser: currentUser,
			product: product,
			canStream: canStream,
		});
	},

	setupController(controller, model) {

		controller.setProperties({
			product: model.product,
			canStream: model.canStream,
			canWatchPlayback: model.canStream,
		});
	},

});
