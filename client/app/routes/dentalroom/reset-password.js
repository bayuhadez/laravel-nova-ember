import Route from '@ember/routing/route';

export default Route.extend({
	model(params)
	{
		return {
			token: params.token
		};
	},
	setupController(controller, model)
	{
		controller.setProperties({
			token: model.token,
			isProcessing: false,
		})
	}
});
