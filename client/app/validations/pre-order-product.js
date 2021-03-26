import {
    validatePresence,
    validateFormat,
    validateNumber
} from 'ember-changeset-validations/validators';

export default {
    product: [
        validatePresence({ presence: true, message: 'Product tidak boleh kosong' }),
    ],
    quantity: [
        validatePresence({ presence: true, message: 'Qty tidak boleh kosong' }),
        validateNumber({ allowBlank: false, message: 'Qty harus numeric' }),
    ],
}
