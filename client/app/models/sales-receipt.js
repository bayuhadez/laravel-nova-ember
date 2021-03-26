//import DS from 'ember-data';
import Model, { attr, belongsTo, hasMany } from '@ember-data/model';
import PpnableMixin from 'rcf/mixins/ppnable';
import { computed } from '@ember/object';
import Promise from 'rsvp';
import { isEmpty } from '@ember/utils';

export default class SalesReceiptModel extends Model.extend(
    PpnableMixin
) {
    // attributes:
    @attr('string') number;
    @attr('boolean') isPpn;
    @attr('number') total;
    @attr('number') subTotal;
    @attr('date') createdAt;
    @attr('date') dueAt;
    @attr('date') updatedAt;
    @attr('date') receiptedAt;
    @attr('string') salesNames;

    // relationships
    @belongsTo('address') address;
    @belongsTo('company') company;
    @belongsTo('user') createdBy;
    @belongsTo('transaction-receipt') transactionReceipt;

    get formattedCreatedAt() {
        let date = this.createdAt;
        let format = 'DD/MM/YYYY hh:mm:ss';
        return moment(date).format(format);
    }

    @computed('isPpn')
    get selectedPpnType()
    {
        const ppnTypes = this.ppnTypes;
        return ppnTypes.find(ppnType => ppnType.id === this.isPpn);
    }

    @computed(
        'transactionReceipt.productTransactionReceipts.[]',
        'transactionReceipt.serviceTransactionReceipts.[]')
    get calculatedTotal()
    {
        let total = 0;
        if(!isEmpty(this.transactionReceipt.get('productTransactionReceipts'))) {
            this.transactionReceipt.get('productTransactionReceipts').forEach(ptr => {
                total = total + ptr.total;
            })
        }
        if(!isEmpty(this.transactionReceipt.get('productTransactionReceipts'))) {
            this.transactionReceipt.get('serviceTransactionReceipts').forEach(str => {
                total = total + str.total;
            })
        }
        return total;
    }

    @computed('calculatedTotal', 'isPpn')
    get calculatedSubTotal()
    {
        let sub = this.calculatedTotal;

        if (this.isPpn) {
            sub = sub + (sub * 10 / 100);
        }

        return sub;
    }

}
