import Model, { attr, belongsTo } from '@ember-data/model';

export default class ProductUnit extends Model {

    @attr('number') conversionRate; 
    @attr('boolean') isPrimary;
    @belongsTo('product') product;
    @belongsTo('unit') unit;
    @belongsTo('unit') convertedUnit;
}
