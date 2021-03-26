import Model, { attr, belongsTo } from '@ember-data/model';

export default class CompanyServiceModel extends Model {
    @attr('number') price;

    //relations
    @belongsTo('service') service;
    @belongsTo('company') company;
}
