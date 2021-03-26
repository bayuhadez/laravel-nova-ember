import Route from '@ember/routing/route';
import { hash } from 'rsvp';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';

export default class AxxMasterWarehousesAddRoute extends Route {

    @service currentUser;

    model()
    {
        return hash({
            warehouse: this.store.createRecord('warehouse'),
            warehouseCategories: this.store.findAll('warehouse-category'),
            companies: this.store.query('company', {
                filter: { weAndChildren: this.currentUser.getCompanyIds() }
            })
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            warehouse: model.warehouse,
            warehouseCategoryOptions: model.warehouseCategories,
            companyOptions: model.companies
        });
    }

    cleanDirtyRacks()
    {
        this.store
            .peekAll('rack')
            .filterBy('isNew', true)
            .forEach(function (rack) {
                rack.deleteRecord();
            });
    }

    @action
    willTransition(transition)
    {
        this.cleanDirtyRacks();
        this.controller.warehouse.rollbackAttributes();
        this.controller.set('warehouseShowErrors', false);
        this.controller.set('rackShowErrors', false);
        return true;
    }
    
}
