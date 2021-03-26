import DS from 'ember-data';

export default DS.Model.extend({
    // attribute
    name: DS.attr('string'),
    created_at: DS.attr('date'),
    updated_at: DS.attr('date'),

    // Relations [
    warehouses: DS.hasMany('warehouse'),
    // ]
});
