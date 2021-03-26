import DS from 'ember-data';

export default DS.Model.extend({
    roles: DS.hasMany(),
    stockDivisions: DS.hasMany(),
    company: DS.belongsTo(),
    user: DS.belongsTo(),
});
