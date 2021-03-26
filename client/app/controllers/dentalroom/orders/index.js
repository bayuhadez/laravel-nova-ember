import Controller from '@ember/controller';
import { sort } from '@ember/object/computed';

export default Controller.extend({
	sortingKey: ['paymentId'],
	sortedOrders: sort('orders', 'sortingKey'),
});
