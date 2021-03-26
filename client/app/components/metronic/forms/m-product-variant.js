import Component from '@ember/component';

export default Component.extend({
    actions: {
        changeProductSize()
        {
            return this.onProductSizeChanged();
        }
    }
});
