import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default class AxxMasterUsersAddRoute extends Route {
    model()
    {
        return hash({
            user: this.store.createRecord('user'),
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            user: model.user,
        });
    }

    willTransition()
    {
        this.controller.rollbackAttributes();
    }
}