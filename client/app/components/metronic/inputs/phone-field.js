import Component from '@ember/component';
import { computed, observer } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { once } from '@ember/runloop';
import { task } from 'ember-concurrency';

export default Component.extend({

    // services
    store: service(),

    // default component properties
    countries: [],
    label: "",
    phone: null,

    loadCountriesData: task(function*() {

        if (isBlank(this.get('countries'))) {
            let countries = yield this.get('store').findAll('country');
            this.set('countries', countries);
        }

    }).on('didUpdateAttrs'),

    numberClasses: computed('showErrors', 'phone.validations.attrs.number.isInvalid', function () {
        let classes = "form-control";
        if (this.get('showErrors') && this.get('phone.validations.attrs.number.isInvalid')) {
            classes += " is-invalid";
        }
        return classes;
    }),

    countryClasses: computed('showErrors', 'phone.validations.attrs.country.isInvalid', function () {
        let classes = "form-control";
        if (this.get('showErrors') && this.get('phone.validations.attrs.country.isInvalid')) {
            classes += " is-invalid";
        }
        return classes;
    }),

    // computed properties:
    selectedPhonecode: computed('phone.country', {
        get(key) {
            let phonecode = this.get('phone.country.phonecode');
            if (!isBlank(phonecode)) {
                return '+' + phonecode;
            } 
            return '';
        }
    }).readOnly(),

});
