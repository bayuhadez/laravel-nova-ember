import Service from '@ember/service';
import { inject as service } from '@ember/service';
import { assign } from '@ember/polyfills';

export default Service.extend({
    intl: service(),

    formatCurrency(number, optionalOptions)
    {
        return this.intl.formatNumber(number, assign(
            {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }, optionalOptions
        ));
    },

    formatDecimal(number, optionalOptions)
    {
        return this.intl.formatNumber(number, assign(
            {
                style: 'decimal',
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            },
            optionalOptions
        ));
    }
});
