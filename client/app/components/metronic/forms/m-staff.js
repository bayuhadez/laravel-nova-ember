import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { computed } from '@ember/object';

export default Component.extend({
    store: service(),

    pageTitle: computed(function() {
        return (this.staff.isNew ? "staff.heading.add" : "staff.heading.edit");
    }),

    didReceiveAttrs()
    {
        let staffCategories = this.store.findAll('staff-category');
        let staffPositions = this.store.findAll('staff-position');
        let regencies = this.store.findAll('regency');

        this.setProperties({
            staffCategories: staffCategories,
            staffPositions: staffPositions,
            regencies: regencies,
        });
    },

    companies: computed('staff.person', function() {
        return this.store.query('company', {
            filter: { notUsedByStaffPerson: this.get('staff.person.id') }
        });
    }),
    
    actions: {
        addStaffCategory(name)
        {
            return this.store.createRecord('staff-category', {
                name: name
            }).save();
        },
        addStaffPosition(name)
        {
            return this.store.createRecord('staff-position', {
                name: name
            }).save();
        },
        togglePersonForm()
        {
            this.toggleProperty('showPersonForm');

            if (this.get('showPersonForm')) {
                // create new person data for the staff
                let person = this.store.createRecord('person');
                this.get('staff').set('person', person);
            } else {
                // remove new person if exists
                if (this.get('staff.person.isNew')) {
                    this.get('staff.person').then((person) => {
                        person.destroyRecord();
                    });
                }

                // select first person from people
                if (!isBlank(this.get('people.firstObject'))) {
                    let person = this.get('people.firstObject');
                    this.get('staff').set('person', person);
                }
            }
        }
    }
});
