import Component from '@ember/component';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils'
import ENV from 'rcf/config/environment';

export default Component.extend({

	session: service(),
	currentUser: service(),

	navs: computed('session.isAuthenticated', 'currentUser.user.id', function () {
		var navs = [];

		var tenantPrefix = ENV.APP.tenant;

		if (this.get('session.isAuthenticated')) {

			var currentUser = this.get('currentUser.user');

			navs = [
				{
					label: 'dashboard',
					route: 'dashboard'
				},
				{
					label: 'seminar',
					route: 'products'
				},
				{
					label: 'my seminar',
					route: 'my-products'
				},
				{
					label: 'shopping cart',
					route: 'shopping-cart'
				}
			];

			if (!isBlank(currentUser.get('id')) && currentUser.get('isMentor')) {
				navs.pushObject({
					label: 'manage seminar',
					route: 'manage-seminar'
				});
			}

		} else {
			navs = [
				{
					label: 'home',
					route: 'dashboard'
				}, {
					label: 'seminar',
					route: 'products'
				}
			];
		}

		// add tenant prefix to all routes
		navs.forEach(function (nav) {
			nav.route = tenantPrefix + '.' + nav.route;
		});

		return navs;
	}),

	init()
	{
		this._super()
	},
});
