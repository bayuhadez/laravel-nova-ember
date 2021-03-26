import Component from '@ember/component';

export default Component.extend({
    // use this component on .hbs file
    // {{metronic/inputs/m-touchspin label="coba" placeholder="0" value=service.name }}
    init() {
        this._super(...arguments);
    },
    didInsertElement() {
    }
});
