import Route from '@ember/routing/route';
import { hash } from 'rsvp';

export default class AxxMasterServicesIndexRoute extends Route {

	model() {
        return hash({
            services: this.store.findAll('service')
        });
    }

    setupController(controller, model){
        controller.setProperties({
            services: model.services,
        });
    }
}
