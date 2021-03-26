import Mixin from '@ember/object/mixin';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default Mixin.create({
    intl: service(),

    ppnTypes: computed(function () {
        return [
            {id: false, name: this.intl.t("options.no")},
            {id: true, name: this.intl.t("options.yes")},
        ];
    }).readOnly(),

    calculatePpnAmount(price)
    {
        return price * 10 / 100;
    }

});
