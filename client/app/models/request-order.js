import DS from 'ember-data';
import { computed } from '@ember/object';
import { requestOrder as constants } from 'rcf/constants';

export default DS.Model.extend({
    // attributes
    number: DS.attr('string'),
    memo: DS.attr('string'),
    status: DS.attr('number'),
    createdAt: DS.attr('date'),
    updatedAt: DS.attr('date'),

    // relationships
    company: DS.belongsTo('company'),
    createdBy: DS.belongsTo('user'),
    staff: DS.belongsTo('staff'),
    staffPosition: DS.belongsTo('staff-position'),
    requestOrderProducts: DS.hasMany('request-order-product'),
    preOrder: DS.belongsTo('pre-order'),

    // computed properties
    formattedCreatedAt: computed('createdAt', function() {
        let date = this.get('createdAt');
        let format = 'DD/MM/YYYY';
        return moment(date).format(format);
    }),

    formattedUpdatedAt: computed('updatedAt', function() {
        let date = this.get('updatedAt');
        let format = 'DD/MM/YYYY';
        return moment(date).format(format);
    }),

    statusLabel: computed('status', function () {
        let statusText = '';
        switch (this.get('status')) {
            case constants.STATUS.REQUEST_ORDER:
                statusText = 'RO';
                break;
            case constants.STATUS.PRE_ORDER:
                statusText = 'PO';
                break;
            case constants.STATUS.DRAFT:
                statusText = 'Draft';
                break;
        }
        return statusText;
    }),

});
