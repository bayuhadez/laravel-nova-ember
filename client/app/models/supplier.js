import DS from 'ember-data';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default DS.Model.extend({
    code : DS.attr('string'),
    faxNumber: DS.attr('string'),
    mobileNumber: DS.attr('string'),
    telephoneNumber: DS.attr('string'),
    top : DS.attr('number', {defaultValue: 0}),
    plafon : DS.attr('number', {defaultValue: 0}),
    accountingNumber : DS.attr('string'),
    information : DS.attr('string'),
    createdAt: DS.attr('date'),
    updatedAt: DS.attr('date'),
    address: DS.attr('string'),
    regency: DS.attr('string'),

    // relationships
    currency : DS.belongsTo('currency'),
    supplierCategory : DS.belongsTo(),
    user: DS.belongsTo(),
    company: DS.belongsTo('company'),
    pic: DS.belongsTo('person'),

    // services
    numberFormatter: service(),

    // computed properties
    formattedCreatedAt: computed('createdAt', function() {
        let date = this.get('createdAt');
        let format = 'DD/MM/YYYY';
        return moment(date).format(format);
    }),

    formattedPlafon: computed('plafon', function() {
        return this.numberFormatter.formatCurrency(this.get('plafon'));
    }),
});
