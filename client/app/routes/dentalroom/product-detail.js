import Route from '@ember/routing/route';

export default Route.extend({
	model(p)
	{
		var product = this.store.find('product', p.id)
		return product
	},
	setupController(controller, model)
	{
		controller.setProperties({
			product: model
		})
	}
});
