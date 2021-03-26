import Model, { attr, belongsTo, hasMany } from '@ember-data/model';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { mapBy } from '@ember/object/computed';

export default class CompanyModel extends Model {

    @service intl;

    @attr('string') name;
    @attr('string') code;
    @attr('string', { defaultValue: 0 }) division;
    @attr('string') telephoneNumber;
    @attr('string') mobileNumber;
    @attr('string') faxNumber;
    @attr('string') valueAddedTaxNumber;
    @attr('string') valueAddedTaxType;
    @attr('date') createdAt;
    @attr('date') updatedAt;

    // Relationships [
    @hasMany('customers') customers;
    @hasMany('product') products;
    @hasMany('service') services;
    @hasMany('company-address') companyAddresses;
    @hasMany('company-service') companyServices;
    @hasMany('company-customer') companyCustomers;
    @hasMany('product-category') productCategories;
    @hasMany('banner') banners;
    @hasMany('warehouse') warehouses;
    @hasMany('staff') staffs;
    @hasMany('rack') racks;
    @hasMany('stock-division') stockDivisions;
    @hasMany('company', { inverse: 'parentCompany' }) childrenCompany;

    @belongsTo('company', { inverse: 'childrenCompany' }) parentCompany;
    @belongsTo('user', { inverse: null }) createdBy;
    @belongsTo('user', { inverse: null }) updatedBy;
    // ]

    @mapBy('companyAddresses', 'address') addresses;
    @mapBy('staffs', 'person') people;

    @computed('stockDivisions.@each.isNew')
    get savedStockDivisions() {
        return this.stockDivisions.filter(function(stockDivision) {
            return !stockDivision.isNew;
        });
    }

    @computed('division')
    get divisionName() {
        switch(this.division) {
            case '0':
                return this.intl.t('company.division.none');
            case '1':
                return this.intl.t('company.division.wholesale');
            case '2':
                return this.intl.t('company.division.retail');
        }
    }

    @computed('valueAddedTaxType')
    get valueAddedTaxTypeName() {
        switch(this.valueAddedTaxType) {
            case '1':
                return this.intl.t('company.valueAddedTaxType.manual');
            case '2':
                return this.intl.t('company.valueAddedTaxType.yes');
            case '3':
                return this.intl.t('company.valueAddedTaxType.no');
        }
    }

    @computed('companyAddresses.@each.isDefault')
    get defaultCompanyAddressName()
    {
        let defaultAddresses = this.companyAddresses.filterBy('isDefault', true);

        if (!isBlank(defaultAddresses)) {
            return defaultAddresses.firstObject.addressName;
        } else {
            return 'No Default Address Selected';
        }
    }

    @computed('companyAddresses.@each.isDefault')
    get defaultAddress()
    {
        let defaultCompanyAddress = this.companyAddresses.findBy('isDefault', true);

        if (!isBlank(defaultCompanyAddress)) {
            return defaultCompanyAddress.address;
        } else {
            return null;
        }
    }

    @computed('name', 'division')
    get formattedDisplayNameAndDivision()
    {
        return (this.name + " - " + this.divisionName);
    }
}
