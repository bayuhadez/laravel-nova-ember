import Route from '@ember/routing/route';
import RSVP from 'rsvp';
import { inject as service } from '@ember/service';

export default Route.extend({
	session: service(),

	beforeModel() {
		if (!this.get('session.isAuthenticated')) {
			this.transitionTo(this.r('login'));
		}
	},

	model() {
		return RSVP.hash({
			license: this.store.createRecord('license')
		});
	},
});
