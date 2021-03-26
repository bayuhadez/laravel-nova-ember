import DS from 'ember-data';
import { inject as service } from '@ember/service';
import { computed } from '@ember/object';
import { isBlank } from '@ember/utils';
import { validator, buildValidations } from 'ember-cp-validations';

const Validations = buildValidations({
    code: [
        validator('warehouse-code-unique', { debounce: 300 }),
        validator('length', { max: 10, description: 'Kode' }),
        validator('format', {
            allowBlank: true,
            regex: /^[a-zA-Z0-9]*$/,
            message: 'Kode harus alphanumeric'
        }),
    ],
    name: [
        validator('warehouse-name-unique', { debounce: 300 }),
        validator('presence', { presence: true, description: 'Nama' }),
        validator('length', { max: 20, description: 'Nama' }),
    ],
    phone: validator('format', { allowBlank: true, regex: /^[0-9]*$/, message: 'Nomor Telephone harus numeric' }),
    person_in_charge: validator('length', { max: 20, description: 'Person In Charge' }),
    address: validator('length', { max: 128, description: 'Alamat' }),
});

export default DS.Model.extend(Validations, {
    // Service [
    intl: service(),
    // ]
    // Attribute
    code: DS.attr('string'),
    name: DS.attr('string'),
    address: DS.attr('string'),
    phone: DS.attr('string'),
    person_in_charge: DS.attr('string'),
    created_at: DS.attr('date'),
    updated_at: DS.attr('date'),

    // Relations [
    companies: DS.hasMany('company'),
    warehouseCategories: DS.hasMany('warehouse-category'),
    racks: DS.hasMany('rack'),
    // ]

    listWarehouseCategories: computed('warehouseCategories.[]', function () {
        let warehouseCategories = this.get('warehouseCategories');
        if (isBlank(warehouseCategories)) {
            return '';
        } else {
            let results = warehouseCategories.mapBy('name');
            return results.join(', ');
        }
    }),

    listCompanies: computed('companies.[]', function () {
        let companies = this.get('companies');
        if (isBlank(companies)) {
            return '';
        } else {
            return companies.mapBy('name').join(', ');
        }
    }),

    totalQuantity: computed('racks.[]', function() {
        let racks = this.get('racks');
        let totalQuantity = 0;

        if (!isBlank(racks)) {
            racks.forEach((rack, index) => {
                totalQuantity += rack.quantity;
            });
        }
        return totalQuantity;
    })
});
