import DS from 'ember-data';

export default DS.Model.extend({
    label: DS.attr('string'),
    productMetaFieldGroup: DS.belongsTo(),
    productMetaValues: DS.hasMany(),
});
