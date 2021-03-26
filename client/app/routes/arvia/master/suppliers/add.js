import Route from '@ember/routing/route';
import { action } from '@ember/object';
import { hash } from 'rsvp';
import { inject as service } from '@ember/service';

export default class AxxMasterSuppliersAddRoute extends Route {
    @service currentUser;

    model(p)
    {
        return hash({
            companies: this.store.findAll('company'),
            supplier: this.store.createRecord('supplier'),
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            companies: model.companies,
            supplier: model.supplier,
        });
    }

    @action
    async willTransition()
    {
        await this.controller.supplier.rollbackAttributes();
        return true;
    }
}
