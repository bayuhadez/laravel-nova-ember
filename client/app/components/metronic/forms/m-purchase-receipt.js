import Component from '@glimmer/component';
import dtc from 'rcf/utils/dtcolumn';
import { A } from '@ember/array';
import { computed, get } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { action } from '@ember/object';
import { tracked } from '@glimmer/tracking';

export default class MetronicFormsMPurchaseReceiptComponent extends Component {

    @service currentUser;
    @service intl;
    @service store;

    @tracked isEditingProductTransactionReceipt;
    @tracked productTransactionReceipt = null;
    @tracked productTransactionReceiptShowErrors = false;

    includeParameters = 'product,product-unit.unit,product-stock-movements,pre-order-product.pre-order';
    fieldParameters = {
        'pre-order-products': 'id',
        'pre-orders': 'id',
    };

    constructor()
    {
        super(...arguments);
        this.currencies = this.store.findAll('currency');
        this.isEditingProductTransactionReceipt = this.args.isEditingProductTransactionReceipt;
        this.showErrors = false;
        this.user = this.currentUser.get('user');

        if (this.args.purchaseReceipt.isNew) {
            // auto select first company
            if (!isBlank(this.args.companies)) {
                this.args.purchaseReceipt.company = this.args.companies.objectAt(0);
            }
            this.args.purchaseReceipt.receiptedAt = new Date();
            this.args.purchaseReceipt.isPpn = false;
            this.args.purchaseReceipt.createdBy = this.currentUser.get('user');
        }
    }

    /* --- computed properties: --- */
    get filterParameters ()
    {
        let filters = {};

        if (!this.args.purchaseReceipt.isNew) {
            filters.inTransactionReceipts = [get(this.args.purchaseReceipt, 'transactionReceipt.id')];
        } else {
            filters.inTransactionReceipts = [null];
        }

        return filters;
    }

    get columns()
    {
        return [
            dtc.create({
                name: this.intl.t('product.attr.code'),
                valuePath: 'product.code',
            }),
            dtc.create({
                name: this.intl.t('product.attr.name'),
                valuePath: 'product.name',
            }),
            dtc.create({
                name: this.intl.t('product_transaction_receipt.attr.foreign_price'),
                valuePath: 'formattedForeignPrice',
            }),
            dtc.create({
                name: this.intl.t('product_transaction_receipt.attr.price'),
                valuePath: 'formattedPrice',
            }),
            dtc.create({
                name: this.intl.t('product_transaction_receipt.attr.quantity'),
                valuePath: 'quantity',
            }),
            dtc.create({
                name: this.intl.t('unit.attr.name'),
                valuePath: 'productUnit.unit.name',
            }),
            dtc.create({
                name: this.intl.t('pre_order_product.attr.discount'),
                valuePath: 'discounts',
            }),
            dtc.create({
                name: this.intl.t('pre_order_product.attr.sub_total'),
                valuePath: 'formattedSubTotal',
            }),
            dtc.create({
                name: this.intl.t('pre_order_product.attr.cost'),
                valuePath: 'cost',
            }),
            dtc.create({
                name: this.intl.t('pre_order_product.attr.total'),
                valuePath: 'formattedTotal',
            }),
            dtc.create({
                name: this.intl.t('product_transaction_receipt.attr.foreign_price_per_pcs'),
                valuePath: 'formattedForeignPricePerPcs',
            }),
            dtc.create({
                name: this.intl.t('product_transaction_receipt.attr.price_per_pcs'),
                valuePath: 'formattedPricePerPcs',
            }),
            dtc.create({
                name: '',
                valuePath: '_actionsColumn',
                buttons: [
                    {preset: 'edit'},
                    {preset: 'delete'},
                    {
                        preset: 'buttonAction',
                        class: 'btn-icon btn-circle',
                        icon: 'flaticon2-box-1',
                        onAction: this.args.openProductPlacement
                    },
                ]
            }),
        ];
    }

    get preOrderFilterParameters()
    {
        let filters = {};

        if (!this.args.purchaseReceipt.get('isNew')) {
            filters.inPurchaseReceipts = [this.args.purchaseReceipt.id];
        } else {
            filters.inPurchaseReceipts = null;
        }

        return filters;
    }

    get preOrderColumns() {
        return A([
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
                buttons: [
                    {preset: 'delete'},
                ]
            }),
        ]);
    }

    /* --- methods: --- */
    createProductTransactionReceipt()
    {
        this.productTransactionReceipt = this.store.createRecord('product-transaction-receipt');
        this.productTransactionReceipt.transactionReceipt = this.args.transactionReceipt;
    }

    rollbackProductTransactionReceipt()
    {
        this.productTransactionReceipt.rollbackAttributes();
    }

    @action choosePpnType(ppnType)
    {
        this.args.purchaseReceipt.isPpn = ppnType.id;
    }

    @action chooseRoundingType(roundingType)
    {
        this.args.purchaseReceipt.roundingType = (isBlank(roundingType) ? null : roundingType.id);
    }

    @action chooseRoundingValue(roundingValue)
    {
        this.args.purchaseReceipt.roundingValue = (isBlank(roundingValue) ? null : roundingValue.id);
    }

    @action deleteProductTransactionReceiptRecord(productTransactionReceipt)
    {
        if (productTransactionReceipt.isNew) {
            this.args.newProductTransactionReceipts.removeObject(productTransactionReceipt);
        }
        productTransactionReceipt.destroyRecord();
    }

    @action editProductTransactionReceiptRecord(productTransactionReceipt)
    {
        if (isBlank(productTransactionReceipt)) {
            this.createProductTransactionReceipt();
        } else {
            this.productTransactionReceipt = productTransactionReceipt;
        }

        this.isEditingProductTransactionReceipt = true;
    }

    @action addProductTransactionReceipt()
    {
        let self = this;
        let productTransactionReceipt = this.productTransactionReceipt;
        let purchaseReceipt = this.args.purchaseReceipt;

        KTApp.blockPage();

        if (productTransactionReceipt.get('validations.isValid')) {
            return new Promise((resolve, reject) => {
                if (!isBlank(productTransactionReceipt)) {
                    if (purchaseReceipt.isNew) {
                        if (!productTransactionReceipt.isInTable) {
                            self.args.newProductTransactionReceipts.pushObject(productTransactionReceipt);
                        }
                        self.productTransactionReceiptShowErrors = false;
                        resolve(productTransactionReceipt);
                    } else {
                        productTransactionReceipt.purchaseReceipt = purchaseReceipt;
                        // save
                        productTransactionReceipt
                            .save()
                            .then(() => {
                                this.args.refreshProductList();
                                self.productTransactionReceiptShowErrors = false;
                                resolve(productTransactionReceipt);
                            })
                            .catch((response) => {
                                self.qswal.e(response);
                                reject(response);
                            });
                    }
                } else {
                    reject();
                }
            }).then((productTransactionReceipt) => {
                productTransactionReceipt.isInTable = true;
                this.createProductTransactionReceipt();
            }).finally(() => {
                KTApp.unblockPage();
                this.productTransactionReceiptShowErrors = false;
            });
        } else {
            KTApp.unblockPage();
            this.productTransactionReceiptShowErrors = true;
        }
    }

    @action cancelProductTransactionReceipt()
    {
        this.rollbackProductTransactionReceipt();
        this.productTransactionReceipt = null;
        this.isEditingProductTransactionReceipt = false;
        this.refreshData = true;
    }

}
