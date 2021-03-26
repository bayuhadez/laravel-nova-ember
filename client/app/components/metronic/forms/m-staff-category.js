import Component from '@ember/component';
import { inject as service } from '@ember/service';

export default Component.extend({
    store: service(),

    actions: {
        addStaffCategory(name)
        {
            return this.store.createRecord('staff-category', {
                name: name
            }).save();
        },
    }
});
