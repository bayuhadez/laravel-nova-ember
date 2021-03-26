import Mtext from 'rcf/components/metronic/inputs/m-text';

export default Mtext.extend({
    layoutName: 'components/metronic/inputs/m-text',

    didRender() {
        this._super(...arguments);

        let arrows;

        if (KTUtil.isRTL()) {
            arrows = {
                leftArrow: '<i class="la la-angle-right"></i>',
                rightArrow: '<i class="la la-angle-left"></i>'
            };
        } else {
            arrows = {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            };
        }

        $(this.$('input')).datetimepicker({
            rtl: KTUtil.isRTL(),
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom left",
            templates: arrows,
            format: "dd/mm/yyyy hh:mm:ss"
        });
    }
});
