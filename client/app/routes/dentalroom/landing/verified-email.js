import Route from '@ember/routing/route';
import { inject as service } from '@ember/service'

export default Route.extend({
	currentUser: service('current-user'),

	beforeModel() {
		this.get('currentUser').load();
	},
});
