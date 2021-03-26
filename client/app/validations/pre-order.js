import {
    validatePresence,
    validateFormat
} from 'ember-changeset-validations/validators';

export default {
    company: [
        validatePresence({ presence: true, message: 'Cabang tidak boleh kosong' }),
    ],
    orderedAt: [
        validatePresence({ presence: true, message: 'Tanggal tidak boleh kosong' }),
    ],
    isPpn: [
        validatePresence({ presence: true, message: 'Tipe PPN tidak boleh kosong' }),
    ],
    supplier: [
        validatePresence({ presence: true, message: 'Supplier tidak boleh kosong' }),
    ]
}
