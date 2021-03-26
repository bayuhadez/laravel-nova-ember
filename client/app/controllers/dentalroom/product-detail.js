import Controller from '@ember/controller'
import { inject } from '@ember/service'

export default Controller.extend({
	shoppingCart: inject(),

	actions: {
		onAddToCartClicked()
		{
			var product = this.get('product')

			// add the product to the cart
			if (this.shoppingCart.add(product)) {
				// notify user that the product is added to the cart
				let title = "Success"

				let message = "You've successfully added "  +
					product.get('name') +
					" by " +
					product.get('hintText')

				let icon = "success"

				swal(title, message, icon)
			}
		}
	}
});
