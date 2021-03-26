import Model, { attr, belongsTo, hasMany } from '@ember-data/model';
import { computed } from '@ember/object';
import { isBlank } from '@ember/utils';

/**
 * This model represent additional data customer for each company
 */
export default class CustomerCompanyModel extends Model {

    ppnOptions = [
        { name: 'Tidak', value: 0 },
        { name: 'Ya', value: 1 }
    ];

    @attr('number', { defaultValue: 0 }) creditLimit;
    @attr('number', { defaultValue: 0 }) termOfPayment;
    @attr('number') ppn;

    // relations:
    @belongsTo('company') company;
    @belongsTo('customer') customer;
    @hasMany('staffs') staffs;

    @computed('staffs.[]')
    get staffsName() {
        if (!isBlank(this.staffs)) {
            return this.staffs.mapBy('name').join(', ');
        }
        return '';
    }

    @computed('ppn')
    get ppnName() {
        switch(this.ppn) {
            case 0:
                return 'Tidak';
            case 1:
                return 'Ya';
        }
    }

    @computed('ppn')
    get selectedPpn() {
        return this.ppnOptions.find(x => x.value === this.ppn);
    }
}
