import Model, { attr, belongsTo, hasMany } from '@ember-data/model';

export default class AddressModel extends Model {

    @attr('string') address;
    @attr('string') pobox;
    @attr('date') createdAt;
    @attr('date') updatedAt;

    @belongsTo('country') country;
    @belongsTo('province') province;
    @belongsTo('regency') regency;

    @hasMany('company-address') companyAddresses;
    @hasMany('person-address') personAddresses;

}
