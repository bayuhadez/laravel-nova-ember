import {
    validatePresence,
    validateLength,
    validateFormat
} from 'ember-changeset-validations/validators';
import validateCompanyCodeUnique from 'rcf/validators/company-code-unique';

export default {
    code: [
        // this is not working, ember-changeset-validations have bug on class validator
        // see https://github.com/poteto/ember-changeset-validations/issues/260
        //new validateCompanyCodeUnique(),
        validateLength({ max: 10, message: 'Kode terlalu panjang, maksimal 10 karakter' }),
        validateFormat({ allowBlank: true, regex: /^[a-zA-Z0-9]*$/, message: 'Kode harus alphanumeric' }),
    ],
    name: [
        validatePresence({ presence: true, message: 'Nama tidak boleh kosong' }),
    ],
    division: [
        validatePresence({ presence: true, message: 'Divisi tidak boleh kosong' }),
    ],
    valueAddedTaxType: [
        validatePresence({ presence: true, message: 'Tipe PPN tidak boleh kosong'})
    ],
    valueAddedTaxNumber: [
        validateFormat({ allowBlank: true, regex: /^[0-9]*$/, message: 'Set Nomor PPN harus numeric' }),
    ]
}
