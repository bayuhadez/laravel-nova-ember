import Model, { attr, belongsTo, hasMany } from '@ember-data/model';

export default class RegencyModel extends Model {

    @attr('string') name;
    @hasMany('address') addresses;
    @belongsTo('province') province;

}
