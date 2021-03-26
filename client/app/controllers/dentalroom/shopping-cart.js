import Controller from '@ember/controller'
import { inject as service } from '@ember/service'
import { isBlank } from '@ember/utils'
import { computed } from '@ember/object';
import { validator, buildValidations } from 'ember-cp-validations';

const Validations = buildValidations({
	voucher_code: [
		validator('voucher-available', { debounce: 500 })
	],
});

export default Controller.extend(Validations, {

	currentUser: service(),
	intl: service(),
	numberFormatter: service(),
	orderService: service(),
	shoppingCart: service(),
	verificationEmailAlert: service(),

	init() {
		this._super(...arguments);
		this.setProperties({
			voucher_code: null,
			price_discount: 0,
			isVoucherUsed: false,
			voucher: null
		});
	},

	totalPrice: computed('shoppingCart.totalPrice', 'price_discount', function () {

		let totalPrice = this.shoppingCart.totalPrice;

		if (!isBlank(this.get('price_discount'))) {
			totalPrice -= this.get('price_discount');
		}
		return this.numberFormatter.formatCurrency(totalPrice);
	}),

	actions: {
		redeemVoucher() {
			let self = this;

			self.validate().then(({ validations }) => {

				if (validations.get('isValid')) {
					self.get('store').query('voucher', {
						filter: { name: self.get('voucher_code')}
					}).then((data) => {
						let voucher = data.get('firstObject');
						self.set('price_discount', voucher.get('amount'));
						self.set('isVoucherUsed', true);
						self.set('voucher', voucher);
					});
				}
			});

		},

		removeProduct(productId) {
			if (confirm("Apakah Anda yakin ingin menghapus product dari keranjang belanja?")) {
				this.get('shoppingCart').remove(productId);
			}
		},

		clearProduct() {
			if (confirm("Apakah Anda yakin ingin menghapus semua product dari keranjang belanja?")) {
				this.get('shoppingCart').clear();
			}
		},

		checkout() {
			let self = this;
			let isLoggedIn = !isBlank(this.get('currentUser.user'));

			// if guest user, redirect to login
			if (!isLoggedIn) {
				this.transitionToRoute(self.r('login'));

			} else if (!self.get('currentUser.user.isEmailVerified')) {
				// check isEmailVerified

				self.get('verificationEmailAlert').showIfNotVerified();

				swal({
					type: "error",
					icon: "error",
					text: this.intl.t("You cannot checkout before email is verified.")
				});

			} else {
				// process the payment
				let productIds = this.get('shoppingCart.productIds');
				let voucherId = this.get('voucher.id');

				this.get('orderService')
					.createOrderToGetToken(productIds, voucherId).then(function (order) {
						// clean shopping-cart
						self.get('shoppingCart').clear();

						// go to checkout
						self.transitionToRoute(self.r('checkout'), order.get('id'));
					});
			}
		},
	}
});
