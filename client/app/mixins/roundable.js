import Mixin from '@ember/object/mixin';
import { computed } from '@ember/object';

export default Mixin.create({

    roundingTypes: computed(function () {
        return [
            {id: 1, name: this.intl.t("roundable.rounding_type.up")},
            {id: 2, name: this.intl.t("roundable.rounding_type.down")},
            {id: 3, name: this.intl.t("roundable.rounding_type.normal")},
        ];
    }).readOnly(),

    roundingValues: computed(function () {
        return [
            {id: 0.01, name: "0.01"},
            {id: 0.1, name: "0.1"},
            {id: 0.5, name: "0.5"},
            {id: 1, name: "1"},
            {id: 5, name: "5"},
            {id: 10, name: "10"},
            {id: 50, name: "50"},
            {id: 100, name: "100"},
        ];
    }).readOnly(),
});
