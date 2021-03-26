import Controller from '@ember/controller';
import ENV from 'rcf/config/environment';
import ProductMovementRef from 'rcf/utils/product-movement-reference';
import { A } from '@ember/array';
import { action, computed, set } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank, isEmpty } from '@ember/utils';
import { preOrder as PoConstants } from 'rcf/constants';
import { tracked } from '@glimmer/tracking';

export default class AxxPurchasesPurchaseReceiptsAddController extends Controller
{
    @service ajaxHelperService;
    @service currentUser;
    @service moneyService;
    @service numberFormatter;
    @service qswal;
    @service store;

    @tracked isPoEditing = false;
    @tracked preOrderRefreshData = false;
    @tracked refreshData = false;
    @tracked showErrors = false;
    companies = A();
    newProductTransactionReceipts = A();
    preOrders;
    purchaseReceipt = null;
    selectedPreOrders;
    suppliers = A();
    @tracked transactionReceipt = null;

    // attributes for product-placement:
    @tracked inProductStockMovementFilterParameters;
    @tracked isProductPlacement = false;
    @tracked refreshProductStockMovementData = false;
    @tracked selectedProductTransactionReceipt = null;
    @tracked productTransactionReceiptRows;
    @tracked productMovementRefs = A();
    skippedSteps = [0];

    constructor()
    {
        super(...arguments);
    }
    
    get isPreOrderModalDisabled()
    {
        return isBlank(this.transactionReceipt.get('supplier.id'));
    }

    // --- COMPUTED PROPERTIES : ---
    @computed('selectedProductTransactionReceipt.productStockMovements.[]')
    get newInProductStockMovements()
    {
        if (isBlank(this.selectedProductTransactionReceipt)) {
            return A([]);
        } else {
            return this.selectedProductTransactionReceipt
                .productStockMovements
                .filter((productStockMovement) => productStockMovement.isNew);
        }
    }

    @computed(
        'productTransactionReceiptRows.[].total',
        'newProductTransactionReceipts.[].total'
    )
    get subTotal()
    {
        let total = 0;

        if (!isBlank(this.productTransactionReceiptRows)) {
            this.productTransactionReceiptRows.forEach(row => {
                total = total + parseFloat(row.total);
            })
        }

        if (!isBlank(this.newProductTransactionReceipts)) {
            this.newProductTransactionReceipts.forEach(row => {
                total = total + parseFloat(row.total);
            });
        }

        return (total < 0) ? 0 : total;
    }

    @computed(
        'subTotal',
        'formRecord.isPpn'
    )
    get ppn()
    {
        let ppn = 0;
        if (this.formRecord.isPpn) {
            ppn = this.subTotal * 10 / 100;
        }
        return ppn;
    }

    @computed(
        'subTotal',
        'ppn',
        'formRecord.discounts'
    )
    get grandTotal()
    {
        let subTotal = this.subTotal;
        let ppn = 0;
        let discounts = this.formRecord.discounts;

        if (!isBlank(discounts)) {
            subTotal = this.moneyService.calculateDiscount(discounts, subTotal);
        }

        if (subTotal <= 0) {
            return 0;
        }

        return (subTotal + this.ppn);
    }

    @computed('subTotal')
    get formattedSubTotal()
    {
        return this.numberFormatter.formatCurrency(this.subTotal);
    }

    @computed('ppn')
    get formattedPpn()
    {
        return this.numberFormatter.formatCurrency(this.ppn);
    }

    @computed('grandTotal')
    get formattedGrandTotal()
    {
        return this.numberFormatter.formatCurrency(this.grandTotal);
    }

    // --- ACTIONS : ---
    /**
     * 1. save PurchaseReceipt
     * 2. save new ProductTransactionReceipt
     * 3. save PreOrders
     */
    @action onSaveRecord()
    {
        let self = this;
        // save PurchaseReceipt
        return new Promise((resolve, reject) => {

            self.transactionReceipt.save().then((transactionReceipt) => {

                self.formRecord.transactionReceipt = transactionReceipt;

                // only save preOrders relation for new purchaseReceipt
                // since preOrders is a container for new record
                if (self.formRecord.isNew) {
                    self.formRecord.preOrders.pushObjects(self.preOrders);
                }

                self.formRecord.save().then((purchaseReceipt) => {
                    let newProductStockMovements = A();

                    if (!isEmpty(self.newProductTransactionReceipts)) {
                        self.newProductTransactionReceipts.forEach((productTransactionReceipt) => {
                            productTransactionReceipt.purchaseReceipt = purchaseReceipt;
                            productTransactionReceipt.transactionReceipt = transactionReceipt;

                            if (!isEmpty(productTransactionReceipt.productStockMovements)) {
                                productTransactionReceipt
                                    .productStockMovements
                                    .forEach((productStockMovement) => {
                                        newProductStockMovements.pushObject(productStockMovement);
                                    });
                            }
                        });
                    }

                    // save all newProductTransactionReceipts
                    Promise.all(
                        self.newProductTransactionReceipts.invoke('save'),
                    ).then(() => {
                        Promise.all(
                            newProductStockMovements.map(model => {return model.save()})
                        ).then(() => {
                            // set empty newProductTransactionReceipts
                            set(self, 'newProductTransactionReceipts', A());
                            set(self, 'preOrders', A());

                            resolve();
                        }).catch((response) => {
                            this.showErrors = true;
                            reject(response);
                        }).finally(() => {
                            // refresh productTransactionReceipt
                            self.refreshData = true;
                        });
                    }).catch((response) => {
                        this.showErrors = true;
                        reject(response);
                    });
                }).catch((response) => {
                    this.showErrors = true;
                    reject(response);
                });
            }).catch((response) => {
                this.showErrors = true;
                reject(response);
            });
        });
    }

    @action goToIndex()
    {
        this.transitionToRoute('axx.purchases.purchase-receipts.index');
    }

    @action async onSavePreOrders(modal)
    {
        let self = this;

        if (isEmpty(self.selectedPreOrders)) {
            modal.modal('hide');
            return false;
        }

        KTApp.blockPage();

        let promiseAssignSelectedPreOrders = await this
            .promiseAssignSelectedPreOrders(self.selectedPreOrders);

        let preOrderIds = self.selectedPreOrders.mapBy('id');
        let newProductTransactionReceipts = A();

        // load pre-order-products
        return self.store.query('pre-order-product', {
            filter: { inPreOrders: preOrderIds },
            include: 'product,product-unit.unit,pre-order',
            fields: {'pre-orders': 'id'},
        }).then(products => {

            // compose all preOrderProducts become productTransactionReceipts
            products.forEach(function (poProduct) {
                let newPoProduct = self.createProductFromPreOrderProduct(poProduct);
                newProductTransactionReceipts.pushObject(newPoProduct);
            })

            self
                .promiseAddNewProductFromPreOrders(newProductTransactionReceipts)
                .finally(() => {

                    self.preOrderRefreshData = true;

                    if (!self.formRecord.isNew) {
                        // refresh datatable
                        self.refreshData = true;
                    }

                    // then set empty selectedPreOrders
                    self.selectedPreOrders = A();

                    self
                        .promiseTriggerPurchaseReceiptCalculation()
                        .finally(() => {
                            modal.modal('hide');

                            KTApp.unblockPage();
                        });
                });
        });

        return false;
    }

    @action refreshProductList()
    {
        set(this, 'refreshData', true);
    }

    @action onPreOrderModalHidden()
    {
        this.isPoEditing = false;
    }

    @action openPreOrderModal()
    {
        this.isPoEditing = true;
    }

    /**
     * push or remove preOrder from selectedPreOrders
     */
    @action toggleSelectedPreOrders(preOrder)
    {
        if (this.selectedPreOrders.isAny('id', preOrder.id)) {
            this.selectedPreOrders.removeObject(preOrder);
        } else {
            this.selectedPreOrders.pushObject(preOrder);
        }
    }

    @action async openProductPlacement(productTransactionReceipt)
    {
        this.isProductPlacement = true;

        // set filter
        this.inProductStockMovementFilterParameters = {
            'inProductTransactionReceipts': [productTransactionReceipt.id],
        };

        set(this, 'selectedProductTransactionReceipt', productTransactionReceipt);

        let productUnit = await productTransactionReceipt.productUnit;

        let conversionRate = 0;
        if (isBlank(productUnit) || productUnit.isPrimary) {
            conversionRate = 1;
        } else {
            conversionRate = await productUnit.get('conversionRate');
        }

        let calculatedQuantity = productTransactionReceipt.quantity * conversionRate;

        let productMovementRef = new ProductMovementRef(
            productTransactionReceipt.product,
            calculatedQuantity
        );

        productMovementRef.inProductStockMovements = await productTransactionReceipt
            .productStockMovements;

        this.productMovementRefs.clear();
        this.productMovementRefs.pushObject(productMovementRef);
    }

    @action onProductPlacementModalHidden()
    {
        set(this, 'selectedProductTransactionReceipt', null);
        set(this, 'isProductPlacement', false);
    }

    @action async addProductStockMovement(productStockMovement)
    {
        let productStockMovements = this
            .selectedProductTransactionReceipt
            .productStockMovements;

        productStockMovement.productTransactionReceipt = this.selectedProductTransactionReceipt;

        if (!this.selectedProductTransactionReceipt.isNew) {
            await productStockMovement.save();
        }

        productStockMovements.pushObject(productStockMovement);

        return new Promise((resolve, reject) => {

            // assign productStockMovement to productTransactionReceipt
            if (!this.selectedProductTransactionReceipt.isNew) {
                this.selectedProductTransactionReceipt
                    .save()
                    .then(() => {
                        this.refreshProductStockMovementData = true;
                        resolve();
                    })
                    .catch((err) => {
                        reject(err);
                    });
            } else {
                resolve();
            }
        });
    }

    @action async editProductStockMovement(productStockMovement)
    {
        if (!this.selectedProductTransactionReceipt.isNew) {
            await productStockMovement.save();
        }

        this.refreshProductStockMovementData = true;
    }

    @action deleteProductStockMovement(productStockMovement)
    {
        KTApp.blockPage();

        let productStockMovements = this
            .selectedProductTransactionReceipt
            .productStockMovements;

        productStockMovements.removeObject(productStockMovement);

        let promise = new Promise((resolve, reject) => {

            if (productStockMovement.isNew) {
                productStockMovement.destroyRecord();
                resolve(productStockMovement);
            } else {
                this
                    .selectedProductTransactionReceipt
                    .save()
                    .then(() => {
                        productStockMovement.destroyRecord();
                        resolve(productStockMovement);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            }
        });

        promise.finally(() => {
            KTApp.unblockPage();
        });
    }

    @action saveProductPlacement()
    {
    }

    @action deletePreOrder(preOrder)
    {
        let self = this;

        this.qswal.confirmDelete(() => {

            KTApp.blockPage();

            // remove from purchaseReceipt relationship
            if (!this.formRecord.isNew) {
                this.formRecord.preOrders.removeObjects(preOrder);
            }

            // remove new productTransactionReceipts that related with preOrder
            let newProductTransactionReceipts = this
                .newProductTransactionReceipts
                .filterBy('preOrderProduct.preOrder.id', preOrder.id);

            newProductTransactionReceipts.forEach((productTransactionReceipt) => {
                self.newProductTransactionReceipts.removeObject(productTransactionReceipt);
            })

            // delete productTransactionReceipts
            let productTransactionReceiptRows = self
                .productTransactionReceiptRows
                .filterBy('preOrderProduct.preOrder.id', preOrder.id)
                .forEach(async(productTransactionReceipt) => {
                    await productTransactionReceipt.destroyRecord();
                });

            // if purchaseReceipt is new then just remove from preOrders array
            if (this.formRecord.isNew || preOrder.isNew) {
                this.preOrders.removeObject(preOrder);
                KTApp.unblockPage();
                this.qswal.delete().s();
            } else {
                let url = (
                    ENV.apiUrl + '/'
                    + ENV.apiNamespace + '/'
                    + 'purchase-receipts/'
                    + self.formRecord.id + '/'
                    + 'relationships/'
                    + 'pre-orders'
                );

                new Promise((resolve, reject) => {
                    self
                        .ajaxHelperService
                        .ajax({
                            'type': 'DELETE',
                            'url': url,
                            data: JSON.stringify({
                                "data": [{ "type": "pre-orders", "id": preOrder.id }]
                            }),
                            'contentType': false,
                            'processData': false,
                        })
                        .done(() => {
                            self.formRecord.preOrders.removeObject(preOrder);
                            resolve();
                        })
                        .fail((err) => {
                            reject(err);
                        });
                }).finally(() => {
                    self.preOrderRefreshData = true;

                    self
                        .promiseTriggerPurchaseReceiptCalculation()
                        .finally(() => {
                            KTApp.unblockPage();
                            this.qswal.delete().s();
                        });
                });
            }
        });
    }

    /** METHODS **/

    /**
     * create preOrderProduct with data from preOrderProduct
     */
    createProductFromPreOrderProduct(preOrderProduct)
    {
        const productTransactionReceipt = this.store.createRecord('product-transaction-receipt');

        // set relationships
        productTransactionReceipt.preOrderProduct = preOrderProduct;
        productTransactionReceipt.preOrderProduct.preOrder = preOrderProduct.preOrder;
        productTransactionReceipt.product = preOrderProduct.get('product');
        productTransactionReceipt.productUnit = preOrderProduct.get('productUnit');
        productTransactionReceipt.unit = preOrderProduct.get('productUnit.unit');
        // set attributes
        productTransactionReceipt.quantity = preOrderProduct.quantity;
        productTransactionReceipt.price = preOrderProduct.purchasePrice;
        productTransactionReceipt.pricePerPcs = preOrderProduct.purchasePricePerPcs;
        productTransactionReceipt.foreignPrice = preOrderProduct.purchasePriceForeign;
        productTransactionReceipt.foreignPricePerPcs = preOrderProduct.purchasePriceForeignPerPcs;
        productTransactionReceipt.discounts = preOrderProduct.discounts;
        productTransactionReceipt.cost = preOrderProduct.cost;
        productTransactionReceipt.subTotal = preOrderProduct.subTotal || 0;
        productTransactionReceipt.total = preOrderProduct.total || 0;

        if (!this.formRecord.isNew) {
            productTransactionReceipt.transactionReceipt = this.formRecord.transactionReceipt;
        }

        return productTransactionReceipt;
    }

    promiseAssignSelectedPreOrders(preOrders)
    {
        let self = this;
        return new Promise((resolve) => {
            preOrders.forEach(function(preOrder) {
                self.preOrders.pushObject(preOrder);
            });
            resolve(self.preOrders);
        });
    }

    promiseAddNewProductFromPreOrders(newProductTransactionReceipts)
    {
        let self = this;
        return new Promise((resolve) => {
            self.newProductTransactionReceipts.pushObjects(newProductTransactionReceipts);
            resolve(newProductTransactionReceipts);
        });
    }

    promiseTriggerPurchaseReceiptCalculation()
    {
        return new Promise((resolve) => {
            resolve();
        });
    }

}
