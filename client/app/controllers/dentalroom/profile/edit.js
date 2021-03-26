import Controller from '@ember/controller';
import ENV from 'rcf/config/environment';
import { isBlank, typeOf } from '@ember/utils';
import { inject as service } from '@ember/service';
import { computed } from '@ember/object';

export default Controller.extend({

	ajaxHelperService: service(),
	store: service(),

	isEmailInputHidden: computed('user.isEmailVerified', function () {
		return this.get('user.isEmailVerified');
	}),

	actions: {

		updateProfile(profile) {

			let self = this;
			let url = ENV.apiUrl + '/' + ENV.apiNamespace + '/profile/update-profile';
			let fd = new FormData();
			
			fd.append('user_id', profile.get('user_id'));
			fd.append('email', profile.get('email'));
			fd.append('first_name', profile.get('first_name'));
			fd.append('last_name', profile.get('last_name'));
			fd.append('address', profile.get('address'));
			fd.append('phone', profile.get('phone'));

			self.set('isProcessing', true);

			let ajaxPromise = new Promise((resolve, reject) => {
				return self.get('ajaxHelperService').ajax({
					url: url,
					type: 'POST',
					data: fd,
					contentType: false,
					processData: false,
					success: function (response) {
						resolve(response);
					},
					error: function (jqXHR/*, textStatus, errorThrown*/) {
						reject(jqXHR);
					},
					beforeSend: function (/*xhr*/) {
						self.set('errorMessages', []);
					},
				});

			})
			.then((response) => {
				swal({
					title: "Update Profile Berhasil",
					icon: "success"
				}).then(() => {
					self.transitionToRoute(self.r('dashboard'));
				});
			})
			.catch((reject) => {
				swal({
					title: "Gagal Update Profile",
					icon: "warning"
				});
			})
			.finally(() => {
				setTimeout(
					() => {
						self.set('isProcessing', false);
					},
					1500
				);
			});

		},

	}
});
