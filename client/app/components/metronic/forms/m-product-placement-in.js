import Component from '@glimmer/component';
import dtc from 'rcf/utils/dtcolumn';
import { A } from '@ember/array';
import { action, computed, set } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank, isEmpty } from '@ember/utils';
import { tracked } from '@glimmer/tracking';

export default class MetronicFormsMProductPlacementInComponent extends Component
{
    @service currentUser;
    @service qswal;
    @service store;
    @service intl;

    @tracked productStockMovement;
    @tracked productStockMovements = A();
    @tracked isEditingInProductStockMovement = false;
    @tracked isUpdatingInProductStockMovement = false;
    @tracked productStockMovement;
    @tracked selectedProductMovementReference;
    @tracked warehouseOptions = A();
    @tracked rackOptions = A();

    includeParameters = 'product,rack.warehouse';
    stockDivisions = A();
    warehouseOptions = A();

    constructor()
    {
        super(...arguments);

        this.stockDivisions = this.args.company.get('stockDivisions') || A();
        this.warehouseOptions = this.args.company.get('warehouses') || A();
        this.fieldParameters = {
            'products': 'name',
            'warehouses': 'code,name',
            'racks': 'code,name,quantity,warehouse',
        };
    }

    /** computed props : */
    get columns()
    {
        return A([
            dtc.create({
                name: "Nama Barang",
                valuePath: 'product.name',
            }),
            dtc.create({
                name: "Gudang",
                valuePath: 'rack.warehouse.name',
            }),
            dtc.create({
                name: "Rak",
                valuePath: 'rack.name',
            }),
            dtc.create({
                name: "Qty",
                valuePath: 'quantity',
            }),
            dtc.create({
                name: "Divisi Stok",
                valuePath: 'stockDivision.name',
            }),
            dtc.create({
                buttons: [
                    {preset: 'edit'},
                    {preset: 'delete'},
                ]
            }),
        ]);
    }

    // --- METHODS: ---
    createInProductStockMovement(product)
    {
        // create a new product-stock-movement
        return this.productStockMovement = this.store.createRecord(
            'product-stock-movement', {
                inOrOut: 1,
                product: product,
            }
        );
    }

    /** actions : */
    @action async addProductStockMovement()
    {
        KTApp.blockPage();
        this
            .args
            .addProductStockMovement(this.productStockMovement)
            .then(() => {
                this.productStockMovement = null;
            })
            .finally(() => {
                // create a new product-stock-movement
                set(this, 'productStockMovement', this.createInProductStockMovement(
                    this.selectedProductMovementReference.product
                ));
                KTApp.unblockPage();
            });
    }

    @action async editProductStockMovement()
    {
        KTApp.blockPage();
        this
            .args
            .editProductStockMovement(this.productStockMovement)
            .then(() => {
                this.productStockMovement = null;
            })
            .finally(() => {
                // create a new product-stock-movement
                set(this, 'productStockMovement', this.createInProductStockMovement(
                    this.selectedProductMovementReference.product
                ));
                KTApp.unblockPage();
            });
    }

    @action closeProductStockMovementForm()
    {
        this.isEditingInProductStockMovement = false;
        this.isUpdatingInProductStockMovement = false;
        this.productStockMovement = null;
    }

    @action selectProductStockMovement(productStockMovement)
    {
        this.selectedProductMovementReference = productStockMovement;
    }

    @action openInProductStockMovementForm()
    {
        this.isEditingInProductStockMovement = true;

        // create a new product-stock-movement
        set(this, 'productStockMovement', this.createInProductStockMovement(
            this.selectedProductMovementReference.product
        ));
    }
    
    @action editInProductStockMovementForm(productStockMovement)
    {
        this.isEditingInProductStockMovement = true;
        this.isUpdatingInProductStockMovement = true;

        // edit an existing product-stock-movement
        productStockMovement.warehouse = productStockMovement.rack.get('warehouse');
        set(this, 'productStockMovement', productStockMovement);
    }

    @action chooseWarehouse(warehouse)
    {
        set(this, 'productStockMovement.warehouse', warehouse);
        set(this, 'productStockMovement.rack', null);
        set(this, 'rackOptions', warehouse.racks);
    }
}
