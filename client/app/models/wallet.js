import Model, { attr, hasMany } from '@ember-data/model';
import { computed } from '@ember/object';
import { isBlank } from '@ember/utils';
import { inject as service } from '@ember/service';

export default class WalletModel extends Model {

    @service intl;

    @attr('string') code;
    @attr('string') name;
    @attr('number') paymentType;
    @attr('number', { defaultValue: 0 }) amount;
    @attr('date') createdAt;
    @attr('date') updatedAt;

    @hasMany('payment-method') paymentMethods;
    @hasMany('company') companies;

    get typeOptions() {
        return [
            { name: this.intl.t('wallet.type_option.in'), value: 0 },
            { name: this.intl.t('wallet.type_option.out'), value: 1 },
            { name: this.intl.t('wallet.type_option.all'), value: 2 },
        ];
    }

    @computed('paymentMethods')
    get displayPaymentMethods() {
        let paymentMethods = this.get('paymentMethods');
        if (isBlank(paymentMethods)) {
            return '';
        } else {
            let results = paymentMethods.mapBy('name');
            return results.join(', ');
        }
    }

    @computed('paymentType')
    get paymentTypeLabel() {
        let type = this.typeOptions.findBy('value', this.paymentType);
        return type.name;
    }

}
