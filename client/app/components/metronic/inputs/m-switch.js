import Component from '@ember/component';

export default Component.extend({
    actions: {
        setData(e) {
            e.preventDefault();
            let value = this.get('value')
            if(value) {
                this.set('value', false)
            } else {
                this.set('value', true)
            }
        }
    }
});
