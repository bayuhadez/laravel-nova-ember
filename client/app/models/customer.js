import Model, { attr, belongsTo, hasMany } from '@ember-data/model';
import { isBlank } from '@ember/utils';
import { inject as service } from '@ember/service';

export default class CustomerModel extends Model {

    @service intl;

    @attr('string') code;
    @attr('string') email;
    @attr('date') createdAt;
    @attr('date') updatedAt;

    @belongsTo('company') company;
    @belongsTo('customer', { inverse: 'childrenCustomer' }) parentCustomer;
    @hasMany('customer', { inverse: 'parentCustomer' }) childrenCustomer;
    @belongsTo('person') person;
    @belongsTo('person') pic;
    @hasMany('company-customer') companyCustomers;
    @hasMany('sales-order') salesOrders;

    get isSubCustomer() {
        let parentCustomer = this.get('parentCustomer');
        if (!isBlank(parentCustomer.get('id'))) {
            return true;
        }
        return false;
    }

    get isSubCustomerText() {
        let parentCustomer = this.get('parentCustomer');
        if (!isBlank(parentCustomer.get('id'))) {
            return this.intl.t('yes');
        }
        return this.intl.t('no');
    }

    get displayName() {
        let name;
        let customer = this;

        if (!isBlank(customer.get('company.name'))) {
            if (!isBlank(customer.get('person.fullname'))) {
                name = customer.get('company.name') + " (" + customer.get('person.fullname') + ")";
            } else {
                name = customer.get('company.name');
            }
        } else {
            name = customer.get('person.fullname');
        }

        return name;
    }

    get telephoneNumber()
    {
        let customer = this;
        let parentCustomer = this.get('parentCustomer');

        if (!isBlank(parentCustomer.get('id'))) {
            customer = parentCustomer;
        }

        if (!isBlank(customer.get('company.id'))) {
            return customer.get('company.telephoneNumber');
        } else if (!isBlank(customer.get('person.id'))) {
            return customer.get('person.telephoneNumber');
        } else {
            return '-';
        }
    }

    get mobileNumber()
    {
        let customer = this;
        let parentCustomer = this.get('parentCustomer');

        if (!isBlank(parentCustomer.get('id'))) {
            customer = parentCustomer;
        }

        if (!isBlank(customer.get('company.id'))) {
            return customer.get('company.mobileNumber');
        } else if (!isBlank(customer.get('person.id'))) {
            return customer.get('person.mobileNumber');
        } else {
            return '-';
        }
    }

    get faxNumber()
    {
        let customer = this;
        let parentCustomer = this.get('parentCustomer');

        if (!isBlank(parentCustomer.get('id'))) {
            customer = parentCustomer;
        }

        if (!isBlank(customer.get('company.id'))) {
            return customer.get('company.faxNumber');
        } else if (!isBlank(customer.get('person.id'))) {
            return customer.get('person.faxNumber');
        } else {
            return '-';
        }
    }

    get defaultCustomerAddressName()
    {
        if (!isBlank(this.company.get('id'))) {
            return this.company.get('defaultCompanyAddressName');
        } else if (!isBlank(this.person.get('id'))) {
            return this.person.get('defaultPersonAddressName');
        }
    }

    get addresses()
    {
        if (!isBlank(this.company.get('id'))) {
            return this.company.get('addresses');
        } else if (!isBlank(this.person.get('id'))) {
            return this.person.get('addresses');
        } else {
            return null
        }
    }

    get defaultAddress()
    {
        if (!isBlank(this.company.get('id'))) {
            return this.company.get('defaultAddress');
        } else if (!isBlank(this.person.get('id'))) {
            return this.person.get('defaultAddress');
        } else {
            return null;
        }
    }

    get people()
    {
        let people = [];
        if (!isBlank(this.company.get('id'))) {
            people = this.company.get('people');
        } else if (!isBlank(this.person.get('id'))) {
            people = [this.person];
        }

        return people;
    }
}
