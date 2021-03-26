import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default class AxxMasterStaffsAddRoute extends Route {
    model()
    {
        return hash({
            staff: this.store.createRecord('staff'),
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