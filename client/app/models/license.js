import DS from 'ember-data';

export default DS.Model.extend({

    fullName: DS.attr('string'),
    number: DS.attr('string'),
    expiryDate: DS.attr('date'),
    photo: DS.attr('file'),

});
