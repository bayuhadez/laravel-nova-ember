import DS from 'ember-data';

export default DS.Model.extend({
    companyName: DS.attr(),
    division: DS.attr(),
    stockDivisionCount: DS.attr(),
});
