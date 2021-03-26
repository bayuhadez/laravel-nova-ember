import {
    validatePresence,
    validateLength,
    validateFormat,
    validateNumber
} from 'ember-changeset-validations/validators';
import validateRackCodeUnique from '../validators/rack-code-unique';
import validateRackNameUniqueInWarehouse from '../validators/rack-name-unique-in-warehouse';

export default {
    code: [
        validateLength({ max: 20, message: 'Kode terlalu panjang, maksimal 20 karakter' }),
        validateRackCodeUnique(),
    ],
    name: [
        validatePresence({ presence: true, message: 'Nama tidak boleh kosong' }),
        validateLength({ max: 10, message: 'Nama terlalu panjang, maksimal 10 karakter' }),
        validateRackNameUniqueInWarehouse(),
    ],
    description: [
        validateLength({ max: 20, message: 'Deskripsi terlalu panjang, maksimal 20 karakter' }),
    ],
    quantity: [
        validateNumber({ allowBlank: true, message: 'Max Qty harus numeric' }),
        validatePresence({ presence: true, message: 'Max Qty tidak boleh kosong' }),
    ],
}
