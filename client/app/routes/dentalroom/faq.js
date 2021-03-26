import Route from '@ember/routing/route';

export default Route.extend({

	model() {
		return this.store.query('faq', {
			fields: {
				'faqs': 'question,sorting-number',
			},
			sort: 'sorting-number,question',
		});
	},

	setupController(controller, model) {
		controller.setProperties({
			faqs: model,
			//currentUser: service('current-user')
		})
	}
});
