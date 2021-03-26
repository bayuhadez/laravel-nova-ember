import {
    validateFormat
} from 'ember-changeset-validations/validators';

export default {
    email: validateFormat({ allowBlank: true, type: 'email' })
}
