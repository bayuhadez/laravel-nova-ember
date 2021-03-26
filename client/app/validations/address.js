import {
    validatePresence,
    validateNumber
} from 'ember-changeset-validations/validators';

export default {
    address: [
        validatePresence({ presence: true, message: 'Alamat tidak boleh kosong' }),
    ],
    pobox: [
        validateNumber({ allowBlank: true, message: 'PO Box harus numeric' }),
    ],
    province: [
        validatePresence({ presence: true, message: 'Provinsi tidak boleh kosong' }),
    ],
    regency: [
        validatePresence({ presence: true, message: 'Kota tidak boleh kosong' }),
    ],
}
