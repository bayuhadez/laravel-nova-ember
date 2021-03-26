import Component from '@glimmer/component';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';

export default class MetronicFormsWalletComponent extends Component {

    @service store;
    @service intl;

    @tracked paymentMethodOptions;

    constructor()
    {
        super(...arguments);
        this.store.findAll('payment-method').then(paymentMethods => {
            this.paymentMethodOptions = paymentMethods;
        })
    }

}