import Component from '@glimmer/component';
import dtc from 'rcf/utils/dtcolumn';
import { inject as service } from '@ember/service';
import { action, computed } from '@ember/object';
import { isBlank } from '@ember/utils';
import { tracked } from '@glimmer/tracking';
import { alias } from '@ember/object/computed';
import { A } from '@ember/array';
import { requestOrder as RoConstants } from 'rcf/constants';
import PreOrderProductValidation from 'rcf/validations/pre-order-product';

export default class MetronicFormsPreOrderComponent extends Component {

    @service intl;
    @service qswal;
    @service store;
    @service moneyService;

    @tracked isRoModalOpen = false;
    @tracked roRefreshDt = false;
    @tracked popRefreshDt = false;

    // Request Order [

    @alias('args.preOrder.data.requestOrders') requestOrders;

    @tracked selectedRequestOrders = A();

    get roFilterParameters()
    {
        let filters = {};

        if (!this.args.preOrder.data.isNew) {
            filters.inPreOrder = this.args.preOrder.id;
        } else {
            filters.inPreOrder = -1;
        }

        return filters;
    }

    get roModalFilterParameters()
    {
        return {
            statusFilter: RoConstants.STATUS.REQUEST_ORDER,
            inCompany: this.args.preOrder.company.id ?? null
        };
    }

    roIncludeParameters = "created-by";

    roColumns = A([
        dtc.create({
            name: this.intl.t('preOrder.table.ro.createdAt'),
            valuePath: 'formattedCreatedAt'
        }),
        dtc.create({
            name: this.intl.t('preOrder.table.ro.number'),
            valuePath: 'number'
        }),
        dtc.create({
            name: this.intl.t('preOrder.table.ro.createdBy'),
            valuePath: 'createdBy.fullname'
        }),
        dtc.create({
            name: '',
            valuePath: '_actionsColumn',
            buttons: [
                { preset: 'delete' }
            ]
        }),
    ]);

    get roModalColumns() {
        return A([
            dtc.create({
                name: this.intl.t('preOrder.table.roModal.createdAt'),
                valuePath: 'formattedCreatedAt',
            }),
            dtc.create({
                name: this.intl.t('preOrder.table.ro.number'),
                valuePath: 'number',
            }),
            dtc.create({
                name: this.intl.t('preOrder.table.ro.createdBy'),
                valuePath: 'createdBy.fullname',
            }),
            dtc.create({
                component: "metronic/datatable-inputs/m-checkbox-select-row",
                checkedRows: this.selectedRequestOrders,
            }),
        ]);
    }

    @action openRoModal()
    {
        this.isRoModalOpen = !this.isRoModalOpen;
    }

    @action closeRoModal()
    {
        this.isRoModalOpen = false;
    }

    @action removeRequestOrder(requestOrder)
    {
        this.qswal.confirmDelete(() => {
            KTApp.blockPage();
            requestOrder.get('requestOrderProducts').then((requestOrderProducts) => {
                let products = requestOrderProducts.map((rop) => rop.get('product'));
                let preOrder = this.args.preOrder.data;
                products.forEach((product) => {
                    let preOrderProduct = preOrder.get('preOrderProducts').find((pop) => pop.get('product.id') == product.get('id'));
                    if (!isBlank(preOrderProduct)) {
                        preOrderProduct.destroyRecord();
                    }
                });
                // detach ro relation 
                requestOrder.set('preOrder', null);
                requestOrder.set('status', RoConstants.STATUS.REQUEST_ORDER);
                // persist changes
                requestOrder.save().then(() => {
                    KTApp.unblockPage();
                    this.qswal.delete().s();
                    this.roRefreshDt = true;
                    this.popRefreshDt = true;
                }).catch(() => {
                    KTApp.unblockPage();
                    this.qswal.delete().e();
                });
            });
        });
    }

    @action bulkSaveRequestOrders()
    {
        KTApp.blockPage();

        let self = this;
        
        new Promise((resolve, reject) => {

            if (isBlank(this.selectedRequestOrders)) {
                resolve();
            }

            this.selectedRequestOrders.forEach(function (requestOrder) {

                self.store.query('request-order-product', {
                    filter: { inRequestOrder: requestOrder.get('id') },
                    include: 'product,product-unit.unit'
                }).then((requestOrderProducts) => {
                    requestOrderProducts.forEach(function (requestOrderProduct) {
                        let pop = self.store.createRecord('pre-order-product', {
                            'preOrder' : self.args.preOrder.data,
                            'product' : requestOrderProduct.get('product'),
                            'productUnit' : requestOrderProduct.get('productUnit'),
                            'unit' : requestOrderProduct.get('unit'),
                            'quantity' : requestOrderProduct.get('quantity'),
                        });
                        pop.save();
                    });
                    resolve(requestOrderProducts);
                }).catch((response) => {
                    reject(response);
                });
            });

        }).then(() => {

            self.selectedRequestOrders.forEach((ro) => {
                ro.set('preOrder', self.args.preOrder.data);
                ro.set('status', RoConstants.STATUS.PRE_ORDER);
                self.requestOrders.pushObject(ro);
            });
            self.requestOrders.invoke('save');
            // empty selected request order
            self.selectedRequestOrders = A();

        }).finally(() => {

            self.isRoModalOpen = false;
            self.roRefreshDt = true;
            self.popRefreshDt = true;
            KTApp.unblockPage();

        });

        return false;
    }
    // ]

    // Pre Order Product [

    PreOrderProductValidation = PreOrderProductValidation;

    @tracked showPreOrderProductErrors = false;
    @tracked isEditingPreOrderProduct = false;
    @tracked preOrderProductSource = null;

    get showPpnAmount()
    {
        if (this.args.preOrder.isPpn) {
            return '10 %';
        } else {
            return '';
        }
    }

    get popFilterParameters()
    {
        let filters = {};

        if (!this.args.preOrder.data.isNew) {
            filters.inPreOrders = [ this.args.preOrder.data.id ];
        } else {
            filters.inPreOrders = [];
        }

        return filters;
    }

    popColumns = A([
        dtc.create({
            name: this.intl.t('pre_order_product.attr.product_name'),
            valuePath: 'product.displayName',
        }),
        dtc.create({
            name: this.intl.t('pre_order_product.attr.purchase_price'),
            valuePath: 'formattedPurchasePrice',
        }),
        dtc.create({
            name: this.intl.t('pre_order_product.attr.purchase_price_foreign'),
            valuePath: 'formattedPurchasePriceForeign',
        }),
        dtc.create({
            name: this.intl.t('pre_order_product.attr.quantity'),
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
            name: this.intl.t('pre_order_product.attr.cost'),
            valuePath: 'cost',
        }),
        dtc.create({
            name: this.intl.t('pre_order_product.attr.sub_total'),
            valuePath: 'formattedSubTotal',
        }),
        dtc.create({
            name: this.intl.t('pre_order_product.attr.purchase_price_per_pcs'),
            valuePath: 'formattedPurchasePricePerPcs',
        }),
        dtc.create({
            name: this.intl.t('pre_order_product.attr.purchase_price_foreign_per_pcs'),
            valuePath: 'formattedPurchasePriceForeignPerPcs',
        }),
        dtc.create({
            name: '',
            valuePath: '_actionsColumn',
            buttons: [
                {preset: 'edit'},
                {preset: 'delete'},
            ]
        }),
    ]);

    popIncludeParameters = "pre-order,product,product-unit.unit";

    @action addPreOrderProduct()
    {
        let newPreOrderProduct = this.store.createRecord('pre-order-product', {
            'preOrder' : this.args.preOrder.data,
        });
        this.preOrderProductSource = newPreOrderProduct;
        this.isEditingPreOrderProduct = true;
    }

    @action editPreOrderProduct(preOrderProduct)
    {
        this.preOrderProductSource = preOrderProduct;
        this.isEditingPreOrderProduct = true;
    }

    @action deletePreOrderProduct(preOrderProduct)
    {
        preOrderProduct.destroyRecord();
    }

    @action async savePreOrderProductForm(popChangeset, event)
    {
        event.preventDefault();
        KTApp.blockPage();
        this.showPreOrderProductErrors = false;
        await popChangeset.validate();

        if (popChangeset.isValid) {
            try {
                let preOrder = await popChangeset.data.preOrder;
                // calculate purchasePricePerPcs [
                let purchasePricePerPcs = popChangeset.purchasePrice;
                if (!popChangeset.productUnit.isPrimary) {
                    purchasePricePerPcs /= popChangeset.productUnit.conversionRate;
                }
                if (preOrder.isPpn) {
                    purchasePricePerPcs += purchasePricePerPcs * 10 / 100;
                }
                // ]

                // calculate purchasePriceForeignPerPcs [
                let purchasePriceForeignPerPcs = purchasePricePerPcs;
                if (preOrder.currencyRate) {
                    purchasePriceForeignPerPcs = purchasePricePerPcs / preOrder.currencyRate;
                }
                // ]

                popChangeset.purchasePricePerPcs = purchasePricePerPcs;
                popChangeset.purchasePriceForeignPerPcs = purchasePriceForeignPerPcs;
                popChangeset.subTotal = this.moneyService.calculatePreOrderProductSubTotal(
                    Number(popChangeset.purchasePrice),
                    Number(popChangeset.discounts),
                    Number(popChangeset.cost),
                    Number(popChangeset.quantity)
                );
                await popChangeset.save();
                KTApp.unblockPage();
                this.qswal.create().s();
                this.popRefreshDt = true;
                this.preOrderProductSource = null;
                this.isEditingPreOrderProduct = false;
            } catch (e) {
                KTApp.unblockPage();
                this.qswal.create().e();
                this.showPreOrderProductErrors = true;
            }
        } else {
            KTApp.unblockPage();
            this.showPreOrderProductErrors = true;
        }
    }

    @action cancelPreOrderProductForm(popChangeset, event)
    {
        popChangeset.rollback();
        this.preOrderProductSource = null;
        this.isEditingPreOrderProduct = false;
    }
    // ]

}
