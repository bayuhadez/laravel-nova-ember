import Component from '@ember/component';

export default Component.extend({
    didInsertElement()
    {
        // activate first tab
        $(this.element).find("a.nav-link").first().addClass('active');
        $(this.element).find(".tab-pane").first().addClass('active');
    }
});
