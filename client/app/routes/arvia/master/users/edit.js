import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default class AxxMasterUsersEditRoute extends Route {
    model(params)
    {
        return hash({
            user: this.store.findRecord('user', params.user_id),
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