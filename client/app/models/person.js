import Model, { attr, belongsTo, hasMany } from '@ember-data/model';
import { computed, set } from '@ember/object';
import { isBlank } from '@ember/utils';
import { mapBy } from '@ember/object/computed';

export default class PersonModel extends Model {

    @attr('string') firstName;
    @attr('string') lastName;
    @attr('string') telephoneNumber;
    @attr('string') mobileNumber;
    @attr('string') faxNumber;

    // Relationship [
    @belongsTo('user') user;
    @belongsTo('regency') regency;
    @hasMany('staff') staffs;
    @hasMany('person-address') personAddresses;
    // ]

    @mapBy('personAddresses', 'address') addresses;
    
    @computed('firstName', 'lastName')
    get fullname()
    {
        if (!isBlank(this.lastName)) {
            return this.firstName + ' ' + this.lastName;
        } else {
            return this.firstName;
        }
    }

    set fullname(fullname)
    {
        let firstName = fullname.split(' ').slice(0, 1).join(' ');
        let lastName = fullname.split(' ').slice(1).join(' ');

        // if name only one word use fullname as firstName
        if (firstName == '' && !isBlank(fullname)) {
            firstName = fullname;
            set(this, 'firstName', firstName);
        } else {
            set(this, 'firstName', firstName);
            set(this, 'lastName', lastName);
        }
    }

    @computed('personAddresses.@each.isDefault')
    get defaultPersonAddressName()
    {
        let defaultAddresses = this.personAddresses.filterBy('isDefault', true);

        if (!isBlank(defaultAddresses)) {
            return defaultAddresses.firstObject.addressName;
        } else {
            return 'No Default Address Selected';
        }
    }

    @computed('personAddresses.@each.isDefault')
    get defaultAddress()
    {
        let defaultPersonAddress = this.personAddresses.findBy('isDefault', true);

        if (!isBlank(defaultPersonAddress)) {
            return defaultPersonAddress.address;
        } else {
            return null;
        }
    }
}
