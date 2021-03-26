import Route from '@ember/routing/route'
import { inject as service } from '@ember/service';

export default Route.extend({

	session: service(),
	currentUser: service(),
	shoppingCart: service(),

	beforeModel(/*transition*/) {
		// clear shoppingCart
		this.invalidateSession();
		this.transitionTo(this.r('dashboard'));
	},

	invalidateSession() {
		// clear shoppingCart
		this.get('shoppingCart').clear();
		this.get('session').invalidate();
	}

});

