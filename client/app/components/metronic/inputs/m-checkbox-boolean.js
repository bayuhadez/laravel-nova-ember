import Component from '@ember/component';

export default Component.extend({
    value: false,
    actions: {
        toggleChecked(value) {
            this.set('value', value);
        }
    }
});
