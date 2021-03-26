import DS from 'ember-data';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default DS.Model.extend({
    code : DS.attr('string'),
    name : DS.attr('string'),
    address : DS.attr('string'),
    faxNumber: DS.attr('string'),
    mobileNumber: DS.attr('string'),
    telephoneNumber: DS.attr('string'),
    pic : DS.attr('string'),
    createdAt : DS.attr('date'),

    // relationships
    country: DS.belongsTo('country'),
    province: DS.belongsTo('province'),
    regency: DS.belongsTo('regency'),
    currency : DS.belongsTo(),
    expeditionCategory : DS.belongsTo(),
    expeditionProducts : DS.hasMany('expedition-product'),

    // services
    numberFormatter: service(),

    // computed properties
    formattedCreatedAt: computed('createdAt', function() {
        let date = this.get('createdAt');
        let format = 'DD/MM/YYYY';
        return moment(date).format(format);
    }),
});
