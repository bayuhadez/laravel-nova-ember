import Route from '@ember/routing/route';
import { action } from '@ember/object';
import { hash } from 'rsvp';
import { inject as service } from '@ember/service';

export default class AxxMasterSuppliersEditRoute extends Route {

    @service currentUser;

    model(params)
    {
        return hash({
            supplier: this.store.findRecord('supplier', params.supplier_id, {
                include: 'company,pic',
            }),
            //suppliers: this.store.findAll('supplier', {
            //    include: 'company,person,pic,parent-supplier,children-supplier'
            //}),
            companies: this.store.findAll('company'),
            //people: this.store.findAll('person')
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            supplier: model.supplier,
            //suppliers: model.suppliers,
            companies: model.companies,
            //people: model.people
        });
    }

    @action
    willTransition()
    {
        this.controller.supplier.rollbackAttributes();
        return true;
    }
}
