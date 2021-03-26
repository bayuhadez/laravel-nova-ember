import Route from '@ember/routing/route';
import ENV from 'rcf/config/environment';
import { hash } from 'rsvp';
import { inject as service } from '@ember/service';

export default Route.extend({

	verificationEmailAlert: service(),

	model() {
		let banners = this.store.findAll('banner', {include: 'product'});
		let sponsors = this.store.findAll('sponsor');
		let products =  this.store.query('product', {
			fields: {'seminar-product-metas': 'start-time'},
			include: 'seminar-product-meta.speaker,user',
			page: {
				number: 1,
				size: 10
			},
			sort: '-created-at',
		});

		return hash({
			banners: banners,
			products: products,
			sponsors: sponsors,
		});
	},

	afterModel(/* model, transition */) {
		this.get('verificationEmailAlert').showIfNotVerified();
	},

	setupController(controller, model) {
		this._super(...arguments);
		controller.setProperties({
			apiUrl: ENV.API_URL,
			banners: model.banners,
			products: model.products,
			sponsors: model.sponsors,
		});
	}
});
