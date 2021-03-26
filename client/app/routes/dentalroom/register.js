import Route from '@ember/routing/route';
import RSVP from 'rsvp';
import { inject as service } from '@ember/service';

export default Route.extend({
	session: service(),

	beforeModel(transition) {

		let self = this;

		if (this.get('session.isAuthenticated')) {

			swal({
				title: "",
				text: "Anda telah terdaftar dalam system kami",
				icon: "warning"
			}).then(() => {
				self.transitionTo(self.r('dashboard'));
			});

			transition.abort();
		}
	},

    model() {
        return RSVP.hash({
			registrar: this.store.createRecord('registrar')
        });
    },

});
