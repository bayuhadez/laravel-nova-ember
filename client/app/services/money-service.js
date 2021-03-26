import Service from '@ember/service';
import { isBlank } from '@ember/utils';

export default Service.extend({

    /**
     * Should consider valid if discount not empty & format is correct
     * Example format "10% 1000 20% 50000" (not implemented yet)
     * @param string discounts
     * @return bool
     */
    isDiscountValid(discounts)
    {
        return !isBlank(discounts);
    },

    /**
     * @param string discounts
     * @param number total
     * @return number total value subtract with given discount
     */
	calculateDiscount(discount, total)
	{
        if (this.isDiscountValid(discount)) {
            let arrayDiscount = discount.split(" ");
            let patternNumeric = /^[0-9]*$/;
            let patternPercentage = /^[0-9]*%$/;
            arrayDiscount.forEach((disc) => {
                // trim empty spaces
                disc.trim();
                if (!isBlank(disc)) {
                    if (patternPercentage.test(disc)) {
                        // calculate discount with percentage
                        let discNumber = parseInt(disc.slice(0,-1));
                        let finalDiscount = total * discNumber / 100;
                        total -= finalDiscount;
                    } else if (patternNumeric.test(disc)) {
                        // calculate discount with direct value
                        let discNumber = parseInt(disc);
                        total -= discNumber;
                    }
                }
            });
        }

        if (isBlank(total) || Number.isNaN(total)) {
            return 0;
        } else {
            return total;
        }
	},

    calculatePreOrderProductSubTotal(purchasePrice, discount = 0, cost = 0, quantity)
    {
        return (purchasePrice - discount + cost) * quantity;
    },
});
