//import DS from 'ember-data';
import Model, { attr, belongsTo, hasMany } from '@ember-data/model';
import PpnableMixin from 'rcf/mixins/ppnable';
import RoundableMixin from 'rcf/mixins/roundable';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';
import { isEmpty } from '@ember/utils';
import { validator, buildValidations } from 'ember-cp-validations';

const Validations = buildValidations({
    number: [
        validator('format', {
            regex: "^[a-zA-Z0-9]*$",
            message: "No.PO harus alphanumeric"
        }),
    ],
});

export default class PurchaseReceiptModel extends Model.extend(
    Validations,
    PpnableMixin,
    RoundableMixin
) {
    @service numberFormatter;

    // attributes:
    @attr('string') number;
    @attr('string') preOrderNumber;
    @attr('string') sonNumber;
    @attr('date') receiptedAt;
    @attr('date') dueAt;
    @attr('number', {defaultValue: 0}) currencyRate;
    @attr('string', {defaultValue: '0'}) discounts;
    @attr('boolean') isPpn;
    @attr('date') createdAt;
    @attr('date') updatedAt;
    @attr('number') subTotal;
    @attr discounts;
    @attr('number') total;
    @attr('string') deliveryOrderNumber;
    @attr('string') taxInvoiceNumber;
    @attr('date') deliveryOrderAt;
    @attr('date') receiptLetterAt;
    @attr roundingType;
    @attr roundingValue;

    // relationships
    @belongsTo('company') company;
    @belongsTo('currency') currency;
    @belongsTo('user') createdBy;
    @belongsTo('transaction-receipt') transactionReceipt;
    @hasMany('pre-order') preOrders;

    @computed('isPpn')
    get selectedPpnType() {
        const ppnTypes = this.ppnTypes;
        return ppnTypes.find(ppnType => ppnType.id === this.isPpn);
    }

    @computed('roundingType')
    get selectedRoundingType() {
        return this
            .get('roundingTypes')
            .find(roundingType => roundingType.id === this.get('roundingType'));
    }

    @computed('roundingValue')
    get selectedRoundingValue() {
        return this
            .get('roundingValues')
            .find(roundingValue => roundingValue.id === this.get('roundingValue'));
    }

    @computed('total')
    get formattedTotal()
    {
        let total = 0;
        if (!isEmpty(this.total)) {
            total = this.total;
        }
        return this.numberFormatter.formatCurrency(total);
    }

    get formattedReceiptAt()
    {
        return moment(this.receiptedAt).format('DD/MM/YYYY');
    }
}
