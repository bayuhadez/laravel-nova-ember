import Model, { attr, belongsTo, hasMany } from '@ember-data/model';

export default class ProvinceModel extends Model {

    @attr('string') name;
    @hasMany('address') addresses;
    @hasMany('regency') regency;

}
