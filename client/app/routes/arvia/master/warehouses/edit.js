import Route from '@ember/routing/route';
import { hash } from 'rsvp';
import { inject as service } from '@ember/service';

export default class AxxMasterWarehousesEditRoute extends Route {

    @service currentUser;

    model(params)
    {
        let warehouse = this.store.findRecord('warehouse', params.warehouse_id, { include: 'companies,racks' });
        let warehouseCategories = this.store.findAll('warehouse-category');
        let companies = this.store.query('company', {
            filter: { weAndChildren: this.currentUser.getCompanyIds() }
        });

        return hash({
            warehouse: warehouse,
            warehouseCategories: warehouseCategories,
            companies: companies
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            warehouse: model.warehouse,
            warehouseCategories: model.warehouseCategories,
            companyOptions: model.companies
        });
    }
}
