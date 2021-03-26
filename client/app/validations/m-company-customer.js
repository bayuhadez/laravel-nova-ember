import {
    validatePresence,
    validateNumber
} from 'ember-changeset-validations/validators';

export default {
    company: [
        validatePresence({ presence: true, message: 'Perusahaan tidak boleh kosong' }),
    ],
    termOfPayment: [
        validatePresence({ presence: true, message: 'TOP tidak boleh kosong' }),
        validateNumber({ allowBlank: true, message: 'TOP harus numeric' }),
    ],
    creditLimit: [
        validatePresence({ presence: true, message: 'Kredit Limit tidak boleh kosong' }),
        validateNumber({ allowBlank: true, message: 'Kredit Limit harus numeric' }),
    ],
}
