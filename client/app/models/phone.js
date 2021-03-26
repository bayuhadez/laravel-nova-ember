import DS from 'ember-data';
import { observer } from '@ember/object';
import { once } from '@ember/runloop';
import { validator, buildValidations } from 'ember-cp-validations';

const Validations = buildValidations({
    country: validator('presence', { presence: true, description: 'Negara' }),
    number: validator('presence', { presence: true, description: 'Nomor' })
});

export default DS.Model.extend(Validations, {
    // attributes:
    countryId: DS.attr('number'),
    number: DS.attr('string'),
    phonecode: DS.attr('string'),
    phonecodeAndNumber: DS.attr('string'),

    // relations:
    country: DS.belongsTo('country'),

    countryChanged: observer('country.id', function () {
        // make sure this is set once
        once(this, 'changeCountryId');
    }),

    // methods:
    changeCountryId()
    {
        this.set('countryId', this.get('country.id'));
    },
});
