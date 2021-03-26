import Route from '@ember/routing/route';
import { inject as service } from '@ember/service'
import { isBlank } from '@ember/utils'

export default Route.extend({

	currentUser: service(),

	beforeModel(transition)
	{
		if (isBlank(this.get('currentUser.user'))) {
			transition.abort();
		}
	},
});
