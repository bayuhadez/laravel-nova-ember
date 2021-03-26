import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { computed } from '@ember/object';
import { isBlank } from '@ember/utils';

export default Component.extend({

	session: service(),
	currentUser: service(),
	shoppingCart: service(),

	errorMessage: null,
	tagName: "li",
	classNames: "nav-item",

	actions: {},

	navs: computed('session.isAuthenticated', 'currentUser.user.id', function () {
		var self = this;
		var navs = [];
		var currentUser = this.get('currentUser.user');

		if (
			self.get('session.isAuthenticated')
			&& !isBlank(this.get('currentUser.user.id'))
		) {
			navs.pushObjects([
				{
					label: 'order history',
					route: 'orders'
				},
			]);

			if (currentUser.get('isStudent')) {
				navs.pushObject({
					label: 'Upgrade To Mentor',
					route: 'mentor-request'
				});
			}

			navs.pushObjects([
				{
					label: 'Reset Password',
					route: 'profile.reset-password'
				},
				{
					label: 'Update Profile',
					route: 'profile.edit',
					param: currentUser.get('id')
				},
				{
					label: 'Logout',
					route: 'logout'
				},
			]);

		} else {
			navs.pushObjects([
				{
					label: 'auth.login',
					route: 'login'
				},
			]);
		}

		// add tenant prefix to all routes
		navs.forEach(function (nav) {
			nav.route = self.r(nav.route);
		});

		return navs;
	})
});
