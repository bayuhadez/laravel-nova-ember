import Route from '@ember/routing/route';

export default Route.extend({
	model()
	{
		let products = this.store.query('product', {
			filter: {
				purchased: true,
			},
		});


		return products;
	},

	setupController(controller, model)
	{
		controller.setProperties({
			products: model
		});
	}
});
