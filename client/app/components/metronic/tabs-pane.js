import Component from '@ember/component';

export default Component.extend({
    classNames: "tab-pane",
    classNameBindings: ["isActive:active"],
    isActive: false,

    didInsertElement()
    {
    }
});
