import DS from 'ember-data';

export default DS.Model.extend({
    amount: DS.attr('number'),

    expedition: DS.belongsTo('expedition'),
    product: DS.belongsTo('product'),
});