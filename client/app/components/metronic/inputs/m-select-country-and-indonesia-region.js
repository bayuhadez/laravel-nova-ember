import Component from '@ember/component';
import { computed, observer } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils'

export default Component.extend({
    intl: service(),
    store: service(),

    allowClear: true,
    countries: null,
    country: null,
    countryLabel: null,
    isLoading: false,
    province: null,
    provinceLabel: null,
    regency: null,
    regencyLabel: null,
    withIndonesiaRegion: true,
    withRegency: true,

    init()
    {
        this._super(...arguments);

        if (isBlank(this.countryLabel)) {
            this.set('countryLabel', this.intl.t('country'));
        }

        // load countries
        if (this.get('countries') === null) {
            this.loadCountries();
        }
    },

    actions: {
        chooseCountry(country)
        {
            if (
                isBlank(country)
                || this.get('country.id') !== country.get('id')
                || ! country.get('isIndonesia')
            ) {
                this.set('regency', null);
                this.set('province', null);
            }

            this.set('country', country);
        }
    },

    // computed properties
    isIndonesiaRegionDisplayed: computed('country', 'withIndonesiaRegion', function () {
        return (
            this.get('withIndonesiaRegion')
            && !isBlank(this.get('country.id'))
            && this.get('country.isIndonesia')
        );
    }),

    // functions:
    loadCountries()
    {
        let self = this;

        self.set('isLoading', true);

        self.store.findAll('country')
            .then(function (countries) {
                self.set('countries', countries);
            }).finally(function () {
                self.set('isLoading', false);
            });
    },

});
