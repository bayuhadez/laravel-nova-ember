import {
    validateNumber
} from 'ember-changeset-validations/validators';

export default {
    faxNumber: [
        validateNumber({ allowBlank: true, message: 'Nomor Fax harus numeric' }),
    ],
    mobileNumber: [
        validateNumber({ allowBlank: true, message: 'Nomor Handphone harus numeric' }),
    ],
    telephoneNumber: [
        validateNumber({ allowBlank: true, message: 'Nomor Telephone harus numeric' }),
    ],
}
