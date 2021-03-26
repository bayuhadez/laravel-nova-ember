import Component from '@ember/component';

export default Component.extend({
    actions: {
        setData(val) {
            return this.set('model', val)
        }
    }
});
