import Route from '@ember/routing/route';
import ENV from 'rcf/config/environment';
import RSVP from 'rsvp';
import { inject as service } from '@ember/service';

export default Route.extend({

	ajaxHelperService: service(),

	beforeModel(transition) {
		// variables
		var url = ENV.apiUrl + '/' + ENV.apiNamespace + '/signed-url-to-manage-route';

		// create promise from ajax post
		var goToManageSeminarPromise = new Promise((resolve, reject) => {
			this.get('ajaxHelperService')
				.ajax({
					type: 'POST',
					url: url,
					contentType: false,
					processData: false,
					success: function (response) {
						resolve(response);
					},
					error: function (jqXHR/*, textStatus, errorThrown*/) {
						reject(jqXHR);
					},
					beforeSend: function (xhr) {
						xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
					},
				});
		});

		goToManageSeminarPromise.then(function (response) {
			window.location.replace(response.redirect);
		});

		goToManageSeminarPromise.catch(function (/*reason*/) {
			transition.abort();
		});

		goToManageSeminarPromise.finally(function (/*reason*/) {
			transition.abort();
		});

		return RSVP.hash({
			goToManageSeminarPromise: goToManageSeminarPromise,
		});
	},
});

