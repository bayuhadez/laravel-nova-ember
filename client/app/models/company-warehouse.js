import DS from 'ember-data';
import { computed } from '@ember/object';

export default DS.Model.extend({

    // Relations [
    company: DS.belongsTo('company'),
    warehouse: DS.belongsTo('warehouse'),
    // ]
});
