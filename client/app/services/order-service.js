import BaseService from 'rcf/services/base-service';
import { inject as service } from '@ember/service';

export default BaseService.extend({
	store: service(),

	init() {
		this._super(...arguments);

		this.urls = this.urls || {
			'createOrder': 'orders/create-with-order-details',
			'getTokenPayment': 'orders/get-token-payment',
		};
	},

	/**
	 * Create order to server with selected products in shopping-cart
	 *
	 * @param number productIds
	 * @param number voucherId
	 * @return Promise with resolve Order object
	 */
	createOrderToGetToken(productIds, voucherId)
	{
		return new Promise((resolve, reject) => {

			let self = this;

			this.get('ajaxHelperService').ajax({
				url: this.apiUrl('createOrder'),
				method: 'POST',
				data: {
					productIds: productIds.toArray(),
					voucherId: voucherId
				},
				success (response) {
					// save to store
					self.store.pushPayload(response);

					// peek Order form store
					let order = self.store.peekRecord('order', response.data.id);

					resolve(order);
				},
				error (jqXHR, ts, et) {
					reject({jqXHR: jqXHR, textStatus: ts, errorThrown: et});
				}
			});
		});
	},

	/**
	 * Get Token payment for snap
	 *
	 * @param integer orderId
	 * @return Promise with resolve Order object
	 */
	getTokenPayment(orderId) {

		return new Promise((resolve, reject) => {

			let self = this;

			self.get('ajaxHelperService').ajax({
				url: this.apiUrl('getTokenPayment'),
				method: 'POST',
				data: {orderId: orderId},
				success (token) {
					resolve(token);
				},
				error (jqXHR, ts, et) {
					reject({jqXHR: jqXHR, textStatus: ts, errorThrown: et});
				}
			});
		});
	},

});
