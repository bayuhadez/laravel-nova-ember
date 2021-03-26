import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import EmberArray from '@ember/array';

export default Controller.extend({
    session: service(),
	isProcessing: false,

    init() {
        this._super(...arguments);
        // input properties [
        this.setProperties({
            errorMessages: []
        });
        // ]
    },

	actions: {
		createUserWithPerson(registrar) {
			let self = this;
			let email = registrar.email;
			let password = registrar.password;

			// clear all errorMessage
			self.set('errorMessages', []);

			self.set('isProcessing', true);

			registrar.save().then(() => {

				// handle success
				self.get('session')
					.authenticate('authenticator:oauth2', email, password)
					.then(() => {
						swal({
							title: "Sukses Registrasi",
							text: "Selamat Anda telah bergabung di Xxx!",
							icon: "success",
						}).then(() => {
							swal({
								title: "Verifikasi Email",
								text: "Penting untuk dilakukan!.\n\nSilahkan periksa inbox email Anda untuk melanjutkan proses email verifikasi",
								icon: "info",
							}).then(() => {
								self.transitionToRoute(self.r('login'));
							});
						});
					}, (error) => {
						// handle error
						swal({
							title: 'Gagal Registrasi',
							text: 'Maaf, tidak dapat melakukan registrasi untuk akun Anda',
							icon: "warning"
						}).then(() => {
							self.transitionToRoute(self.r('login'));
						});
					});

			}).catch((adapterError) => {

				// handle error
				/*
				swal({
					title: 'Gagal Registrasi',
					text: 'Maaf, tidak dapat melakukan registrasi untuk akun Anda',
					icon: "warning"
				}).then(() => {
					self.transitionToRoute('login');
				});
				*/

				let errorMessages = self.get('errorMessages');

				registrar.get('errors').forEach(function (error) {
					errorMessages.push(error.message);
				});

				self.set('errorMessages', errorMessages);
			}).finally(() => {
				self.set('isProcessing', false)
			});
		},
	}
});
