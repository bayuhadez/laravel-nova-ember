import Component from '@ember/component';
import { computed, observer } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { once } from '@ember/runloop';

export default Component.extend({
    store: service(),

    // properties:
    disabled: false,
    searchField: "name",
    placeholderCountry: "",

    init()
    {
        this._super(...arguments);

        // set countries
        if (isBlank(this.get('countries.length'))) {
            this.set('countries', this.store.findAll('country'));
        }
    },

    didReceiveAttrs() {
        this._super(...arguments);

        // set _phone
        if (isBlank(this.get('phone').get('content'))) {
            this.set('_phone', this.store.createRecord('phone'));
        } else {
            this.set('_phone', this.get('phone'));
        }
    },

    countryOrNumberChanged: observer('_phone.number', '_phone.country', function () {
        once(this, 'updatePhone');
    }),

    computedNumberErrors: computed('_phone.errors.number', 'numberErrors', function () {
        if (!isBlank(this.get('numberErrors'))) {
            return this.get('numberErrors');
        } else {
            return this.get('_phone.errors.number');
        }
    }),

    computedCountryErrors: computed('_phone.errors.country-id', 'countryErrors', function () {
        if (!isBlank(this.get('countryErrors'))) {
            return this.get('countryErrors');
        } else {
            return this.get('_phone.errors.country-id');
        }
    }),

    numberClasses: computed('computedNumberErrors', function () {
        let classes = "form-control";

        if (!isBlank(this.get('computedNumberErrors'))) {
            classes += " is-invalid";
        }

        return classes;
    }),

    countryClasses: computed('computedCountryErrors', function () {
        let classes = "form-control";

        if (!isBlank(this.get('computedCountryErrors'))) {
            classes += " is-invalid";
        }

        return classes;
    }),

    // computed properties:
    selectedPhonecode: computed('_phone.country', {
        get(key) {
            let phonecode = this.get('_phone.country.phonecode');
            if (!isBlank(phonecode)) {
                return '+' + phonecode;
            } 
            return '';
        }
    }).readOnly(),

    updatePhone()
    {
        this.set('phone', this.get('_phone'));
    },
});
