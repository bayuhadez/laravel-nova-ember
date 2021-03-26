import Route from '@ember/routing/route';
import ENV from 'rcf/config/environment';
import { hash } from 'rsvp';
import { inject as service } from '@ember/service';

export default Route.extend({
    session: service(),

    beforeModel() {
        if (this.session.isAuthenticated) {
            this.transitionTo(ENV.APP.tenant + '.master');
        } else {
            this.transitionTo(ENV.APP.tenant + '.login');
        }

    },
	model() {
		
	},

	setupController(controller, model) {
		this._super(...arguments);
	}
});
