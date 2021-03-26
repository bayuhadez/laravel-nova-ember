import Route from '@ember/routing/route'
import ENV from 'rcf/config/environment';
import { hash } from 'rsvp';

export default Route.extend({
	model() {
		let products = this.store.findAll('product');
		return hash({
			products: products
		});
	},

	setupController(controller, model) {
		controller.setProperties({
			products: model.products,
			apiUrl: ENV.apiUrl
		})
	}
});
