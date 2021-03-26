import Component from '@ember/component';
import { computed, observer } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils'
import { once } from '@ember/runloop';

export default Component.extend({
    intl: service(),
    store: service(),
    disabled: false,
    withRegency: true,
    isRegencyLoading: false,
    isProvinceLoading: false,

    init()
    {
        this._super(...arguments);

        if (isBlank(this.provinceLabel)) {
            this.set('provinceLabel', this.intl.t('province'));
        }
        if (isBlank(this.regencyLabel)) {
            this.set('regencyLabel', this.intl.t('regency'));
        }

        // load provinces
        if (isBlank(this.get('provinces'))) {
            this.loadProvinces();
        }
    },

    loadProvinces()
    {
        let self = this;

        self.set('isProvinceLoading', true);

        self.store.findAll('province')
            .then(function (provinces) {
                self.set('provinces', provinces);
            }).finally(function () {
                self.set('isProvinceLoading', false);
            });
    },

    actions: {
        chooseProvince(province)
        {
            if (
                isBlank(province)
                || this.get('province.id') !== province.get('id')
            ) {
                this.set('regency', null);
            }

            this.set('province', province);
        }
    },

    // computed properties
    isRegencyDisplayed: computed('province', 'withRegency', function () {
        return (!isBlank(this.get('province.id')) && this.get('withRegency'));
    }),

    isLoading: computed('isProvinceLoading', 'isRegencyLoading', function () {
        return ( this.get('isProvinceLoading') || this.get('isRegencyLoading') );
    }),

    isDisabled: computed('disabled', 'isLoading', function () {
        return ( this.get('disabled') || this.get('isLoading') );
    }),

    // observers
    provinceChanged: observer('province', function () {
        if (!isBlank(this.get('province'))) {
            once(this, 'processReloadRegencies', this.get('province'));
        } else {
            this.set('regency', null);
        }
    }),

    // functions:
    processReloadRegencies(province)
    {
        let self = this;

        if (!isBlank(self.get('province.id'))) {

            self.set('isRegencyLoading', true);

            self.store.findRecord('province', self.get('province.id'))
                .then(function (province) {
                    self.set('regencies', province.get('regencies'));
                }).finally(function () {
                    self.set('isRegencyLoading', false);
                });
        }
    },
});
