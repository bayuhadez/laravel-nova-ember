import {
    validatePresence,
} from 'ember-changeset-validations/validators';

export default {
    code: [
        validatePresence({ presence: true, message: 'Kode tidak boleh kosong' }),
    ],
    name: [
        validatePresence({ presence: true, message: 'Nama tidak boleh kosong' }),
    ],
}
