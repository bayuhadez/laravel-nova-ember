import Controller from '@ember/controller';
import dtc from 'rcf/utils/dtcolumn';
import { A } from '@ember/array';
import { isBlank } from '@ember/utils';
import { action, computed } from '@ember/object';
import { tracked } from '@glimmer/tracking';
import { inject as service } from '@ember/service';

export default class AxxMasterWarehousesAddController extends Controller {

    @service intl;

    @tracked warehouseShowErrors = false;
    @tracked rackShowErrors = false;

    breadcrumbs = {
        title: this.intl.t('warehouse.identifier'),
        route: "axx.master.warehouses",
        subNav: [
            {
                name: this.intl.t('warehouse.heading.add'),
            }
        ],
    };

    /**
     * check if warehouse & racks record valid for submit
     */
    @computed('warehouse', 'warehouse.racks.[]')
    get isFormValid() {
        let racks = this.get('warehouse.racks');
        let isWarehouseValid = this.get('warehouse.validations.isValid');
        let isRacksValid = true;
        racks.forEach((rack) => {
            if (!rack.get('validations.isValid')) {
                isRacksValid = false;
            }
        });

        if (!isWarehouseValid) {
            this.set('warehouseShowErrors', true);
        }
        if (!isRacksValid) {
            this.set('rackShowErrors', true);
        }

        return isWarehouseValid && isRacksValid;
    }

    /**
     * 1. Validate Warehouse Form Data
     * 2. Save Warehouse
     * 3. Save All New Rack Added
     */
    @action
    saveWarehouseForm()
    {
        let self = this;
        let warehouse = this.get('warehouse');
        let warehouseRacks = warehouse.get('racks');
        if (this.get('isFormValid')) {
            return new Promise((resolve, reject) => {
                KTApp.blockPage();
                self.get('warehouse').save().then((warehouse) => {
                    // save all racks
                    Promise.all(
                        warehouseRacks.invoke('save')
                    ).then(() => {
                        self.set('refreshData', true);
                        self.set('warehouseShowErrors', false);
                        self.set('rackShowErrors', false);
                        KTApp.unblockPage();
                        resolve(warehouse);
                        self.qswal.create().s();
                        self.transitionToRoute('axx.master.warehouses.edit', warehouse.get('id'));
                    }).catch((response) => {
                        KTApp.unblockPage();
                        self.qswal.create().e();
                        reject(response);
                    });
                }).catch((response) => {
                    KTApp.unblockPage();
                    self.qswal.create().e();
                    reject(response);
                });
            });
        }
    }
}
