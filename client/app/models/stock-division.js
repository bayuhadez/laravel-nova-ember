import DS from 'ember-data';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';
import { validator, buildValidations } from 'ember-cp-validations';

const divisionOptions = [
    { name: 'Wholesale', value: '1' },
    { name: 'Retail', value: '2' }
];

const Validations = buildValidations({
    company: validator('presence', { presence: true, description: 'Cabang' }),
    division: validator('presence', { presence: true, description: 'Divisi' }),
    description: validator('length', { max: 128, description: 'Deskripsi' }),
});

export default DS.Model.extend(Validations, {
    // Custom Properties [
    divisionOptions,
    // ]
    // Service [
    intl: service(),
    // ]

    description: DS.attr('string'),
    name: DS.attr('string'),
    division: DS.attr('string'),
    created_at: DS.attr('date'),
    updated_at: DS.attr('date'),

    // Relationships
    company: DS.belongsTo('company'),
    productSalesOrders: DS.hasMany('product-sales-order'),

    // Computed Properties
    selectedDivision: computed('division', function() {
        const divisionOptions = this.get('divisionOptions');
        if (!this.get('division')) {
            return null;
        }
        return divisionOptions.find(x => x.value === this.get('division'));
    }),

    divisionName: computed('division', function() {
        switch(this.get('division')) {
            case '0':
                return this.intl.t('company.division.none');
            case '1':
                return this.intl.t('company.division.wholesale');
            case '2':
                return this.intl.t('company.division.retail');
        }
    }),
});
