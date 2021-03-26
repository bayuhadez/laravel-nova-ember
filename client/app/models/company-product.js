import DS from 'ember-data';

export default DS.Model.extend({
    point : DS.attr('number'),
    price : DS.attr('number'),
    receiptPrice : DS.attr('number'),

    //relations
    product : DS.belongsTo('product'),
    company : DS.belongsTo('company'),
});
