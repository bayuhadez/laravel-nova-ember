import DS from 'ember-data';

export default DS.Model.extend({

    // attributes [
    name: DS.attr('string'),
    // ]
    
    // relationships [
    products: DS.hasMany(),
    company: DS.belongsTo(),
    parent: DS.belongsTo('product-category', {inverse: 'children'}),
    children: DS.hasMany('product-category', {inverse: 'parent', async: false}),
    // ]
});
