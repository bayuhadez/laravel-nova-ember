import Component from '@glimmer/component';
import dtc from 'rcf/utils/dtcolumn';
import { action, computed } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { preOrder as PoConstants } from 'rcf/constants';

export default class MetronicFormsMPreOrderPurchaseReceiptComponent extends Component {
    @service intl;

    checkedFlag = 'isChecked';

    constructor() {
        super(...arguments);
    }

    get filterParameters() {
        const filters = {
            doesntHavePurchaseReceipts:"",
            supplier_id:this.args.supplier.get('id'),
        };

        if (!isBlank(this.args.preOrders)) {
            // get list of preOrders' to be excluded
            filters.notIn = this.args.preOrders.mapBy('id');
        }
        return filters;
    }

    @computed('args.preOrders')
    get columns() {
        return [
            dtc.create({
                name: this.intl.t('pre_order.attr.po_ordered_at'),
                valuePath: 'formattedOrderedAt',
            }),
            dtc.create({
                name: this.intl.t('pre_order.attr.number'),
                valuePath: 'number',
            }),
            dtc.create({
                name: this.intl.t('pre_order.rel.createdBy'),
                valuePath: 'createdBy.fullname',
            }),
            dtc.create({
                name: '',
                component: "input-checkbox",
                checked: this.checkedFlag,
                change: this.args.toggleSelectedPreOrders,
            }),
        ];
    }

}
