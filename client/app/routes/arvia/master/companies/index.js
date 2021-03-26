import Route from '@ember/routing/route';
import { hash } from 'rsvp';
import { inject as service } from '@ember/service';

export default Route.extend({
    currentUser: service(),
    
    beforeModel() {
        this.currentUser.loadCurrentUser();
    },
    
    model() {
        return hash({
            companies: this.store.findAll('company'),
        });
    },

    setupController(controller, model){
        controller.setProperties({
            companies: model.companies,
        });
    },

});
