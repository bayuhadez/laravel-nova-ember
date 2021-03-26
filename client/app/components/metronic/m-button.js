import Component from '@ember/component';
import {inject as service} from '@ember/service';
import {isBlank} from '@ember/utils';

export default Component.extend({
    router: service(),
    tagName: "button",
    classNames: "btn btn-primary",
    attributeBindings: ['type', 'disabled'],
    disabled: false,
    click(event)
    {
        if (this.get('action')) {
            event.preventDefault();
            this.get('action')(this.record);
        } else if (this.get('route')) {
            if (!isBlank(this.record)) {
                this.get('router').transitionTo(this.get('route'), this.record.get('id'));
            } else {
                this.get('router').transitionTo(this.get('route'));
            }
        }
    }
});
