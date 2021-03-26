import Component from '@ember/component';
import { computed } from '@ember/object';

export default Component.extend({

    firstBanner: computed('banners.[]', function () {
        if (this.get('banners.length') > 0) {
            return this.get('banners.firstObject');
        } else {
            return null;
        }
    }),

    remainingBanners: computed('banners.[]', function () {
        if (this.get('banners.length') > 1) {
            return this.get('banners').slice(1);
        } else {
            return null;
        }
    })
});
