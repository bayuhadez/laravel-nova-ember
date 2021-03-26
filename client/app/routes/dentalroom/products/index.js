import Route from '@ember/routing/route';
import ENV from 'rcf/config/environment';
import { hash } from 'rsvp';
import { inject as service } from '@ember/service';

export default Route.extend({

	currentUser: service(),

	model() {
		let products = this.store.query('product', {
			filter: {upcoming: true},
			fields: {
				'users': 'name,email',
				'seminar-product-metas': 'is-session-in-progress,start-time,playback-video-file-path'
			},
			include: 'seminar-product-meta.speaker,user',
			sort: '-created-at',
		});

		return hash({
			products: products,
		});
	},

	setupController(controller, model) {
		controller.setProperties({
			products: model.products,
			apiUrl:  ENV.apiUrl,
			currentUser: this.currentUser,
		})
	}

});
