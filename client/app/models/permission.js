import DS from 'ember-data';
import { computed } from '@ember/object';

export default DS.Model.extend({
    name: DS.attr(),

    roles: DS.hasMany(),

    module: computed('name', function () {
        return this.get('name').split('.')[0];
    }),

    ability: computed('name', function () {
        return this.get('name').split('.')[1];
    }),
});
