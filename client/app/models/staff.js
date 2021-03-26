import DS from 'ember-data';
import { computed } from '@ember/object';
import { isBlank } from '@ember/utils';

export default DS.Model.extend({
    code: DS.attr('string'),

    /* Relationships */
    staffCategories: DS.hasMany(),
    staffPositions: DS.hasMany(),
    companyCustomers: DS.hasMany(),
    company: DS.belongsTo(),
    person: DS.belongsTo(),

    /* Computed Properties */
    listStaffCategories: computed('staffCategories.[]', function() {
        let staffCategories = this.get('staffCategories');
        if (isBlank(staffCategories)) {
            return '';
        } else {
            let results = staffCategories.mapBy('name');
            return results.join(', ');
        }
    }),

    listStaffPositions: computed('staffPositions.[]', function() {
        let staffPositions = this.get('staffPositions');
        if (isBlank(staffPositions)) {
            return '';
        } else {
            let results = staffPositions.mapBy('name');
            return results.join(', ');
        }
    }),

    name: computed('person', function() {
        return this.get('person.fullname');
    }),
});
