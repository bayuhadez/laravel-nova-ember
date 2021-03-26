import Controller from '@ember/controller';
import ENV from 'rcf/config/environment';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';

export default Controller.extend({
	// attributes
	ajaxHelperService: service('ajax-helper-service'),

	init() {
		this._super(...arguments);

		this.setProperties({
			errorMessages: [],
		});
	},

	actions: {
		submitMentorLicense: function (license) {
			// variables
			var self = this;
			var url = ENV.apiUrl + '/' + ENV.apiNamespace + '/mentor-request';
			var fd = new FormData();

			// set form data
			fd.append('full-name', license.get('fullName'));
			fd.append('expiry-date', license.get('expiryDate'));
			fd.append('number', license.get('number'));
			fd.append('photo', license.get('photo'));

			// create promise from ajax post
			var ajaxPromise = new Promise((resolve, reject) => {
				this.get('ajaxHelperService')
					.ajax({
						type: 'POST',
						url: url,
						data: fd,
						contentType: false,
						processData: false,
						success: function (response) {
							resolve(response);
						},
						error: function (jqXHR, textStatus/*, errorThrown*/) {
							reject(jqXHR);
						},
						beforeSend: function (xhr) {
							xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
							self.set('errorMessages', []);
						},
					});
			});

			ajaxPromise.then(function (/*response*/) {
				alert('Permintaan menjadi Mentor berhasil dikirim.');
				self.transitionToRoute(self.r('dashboard'));
			});

			ajaxPromise.catch(function (reason) {
				if (!isBlank(reason.responseJSON.errors)) {
					var errors = reason.responseJSON.errors;
					for (var attributeName in errors) {
						self.get('errorMessages').pushObject(errors[attributeName].join('<br>'));
					}
				}
			});

			return false;
		},
	},
});
