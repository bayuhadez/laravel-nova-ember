import Mtext from 'rcf/components/metronic/inputs/m-text';

export default Mtext.extend({
    didInsertElement() {
        $(this.$('input')).daterangepicker({
            buttonClasses: 'btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            autoUpdateInput: true,
        });
    }
});
