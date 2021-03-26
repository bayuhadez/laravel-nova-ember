import DS from 'ember-data';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default DS.Model.extend({
	// attributes
	grossAmount: DS.attr('number'),
	paymentId: DS.attr('string'),
	paymentStatus: DS.attr('number'),
	status: DS.attr('number'),
	userId: DS.attr('number'),
	createdAt: DS.attr('date'),
	updatedAt: DS.attr('date'),

	// relations
    user: DS.belongsTo('user'),
	orderDetails: DS.hasMany('order-details'),
	transaction: DS.belongsTo('transaction'),
	voucher: DS.belongsTo('voucher'),

	// services
	intl: service(),
	numberFormatter: service(),

	// computed properties
	formattedCreatedAt: computed('createdAt', function() {
		let date = this.get('createdAt');
		let format = 'DD/MM/YYYY';
		return moment(date).format(format);
	}),

	formattedGrossAmount: computed('grossAmount', function() {
		return this.numberFormatter
			.formatCurrency(this.get('grossAmount'));
	}),

	statusText: computed('status', function () {

		let text = '';

		switch(this.status) {
			case this.STATUS_PENDING : text = this.get('intl').t('status_label.order.pending');
				break;
			case this.STATUS_FAILED : text = this.get('intl').t('status_label.order.failed');
				break;
			case this.STATUS_PROCESSING : text = this.get('intl').t('status_label.order.processing');
				break;
			case this.STATUS_COMPLETED : text = this.get('intl').t('status_label.order.completed');
				break;
			case this.STATUS_ONHOLD : text = this.get('intl').t('status_label.order.onhold');
				break;
			case this.STATUS_CANCELLED : text = this.get('intl').t('status_label.order.cancelled');
				break;
			case this.STATUS_REFUNDED : text = this.get('intl').t('status_label.order.refunded');
				break;
		}

		return text;
	}),

	paymentStatusText: computed('paymentStatus', function () {

		let text = '';

		switch(this.paymentStatus) {
			case this.PAYMENT_STATUS_PENDING : text = this.get('intl').t('status_label.payment.pending');
				break;
			case this.PAYMENT_STATUS_SETTLEMENT : text = this.get('intl').t('status_label.payment.settlement');
				break;
			case this.PAYMENT_STATUS_DENIED : text = this.get('intl').t('status_label.payment.denied');
				break;
			case this.PAYMENT_STATUS_CHALLENGED : text = this.get('intl').t('status_label.payment.challenged');
				break;
			case this.PAYMENT_STATUS_SUCCESS : text = this.get('intl').t('status_label.payment.success');
				break;
			case this.PAYMENT_STATUS_EXPIRED : text = this.get('intl').t('status_label.payment.expired');
				break;
		}

		return text;
	}),

}).reopen({
	// static attributes :
	STATUS_PENDING: 0,
	STATUS_FAILED: 1,
	STATUS_PROCESSING: 2,
	STATUS_COMPLETED: 3,
	STATUS_ONHOLD: 4,
	STATUS_CANCELLED: 5,
	STATUS_REFUNDED: 6,

	PAYMENT_STATUS_PENDING: 0,
	PAYMENT_STATUS_SETTLEMENT: 1,
	PAYMENT_STATUS_DENIED: 2,
	PAYMENT_STATUS_CHALLENGED: 3,
	PAYMENT_STATUS_SUCCESS: 4,
	PAYMENT_STATUS_EXPIRED: 5,
});
