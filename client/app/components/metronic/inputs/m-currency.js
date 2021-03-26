import Component from '@ember/component';

export default Component.extend({
    disabled: false,
    actions: {
        onupdate(unmasked, masked)
        {
            console.log(unmasked);
            this.set('value', unmasked);
        }
    }
});
