import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { computed } from '@ember/object';

export default Component.extend({
    store : service(),
    pageTitle: computed(function () {
        return (this.expedition.isNew ? "expedition.heading.add" : "expedition.heading.edit");
    }),

    init() {

        this._super(...arguments);

        this.setProperties({
            countries : this.store.findAll('country'),
            currencies : this.store.findAll('currency'),
            categories : this.store.findAll('expedition-category'),
        })
    },

    actions : {
        addExpeditionCategory(name) {
            return this.get('store').createRecord('expedition-category', {
                name : name,
            }).save();
        }
    }

});
