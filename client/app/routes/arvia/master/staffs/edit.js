import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default class AxxMasterStaffsEditRoute extends Route {
    model(params)
    {
        return hash({
            staff: this.store.findRecord('staff', params.staff_id, {
                include: 'person,company,staffCategories'
            }),
            people: this.store.findAll('person'),
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            staff: model.staff,
            people: model.people,
        });
    }

    willTransition()
    {
        this.controller.rollbackAttributes();
    }
}