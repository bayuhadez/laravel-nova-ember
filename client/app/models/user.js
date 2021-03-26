import DS from 'ember-data';
import { computed } from '@ember/object';
import { isBlank } from '@ember/utils';

export default DS.Model.extend({
    email: DS.attr('string'),
    password: DS.attr('string'),
    passwordConfirmation: DS.attr('string'),
    name: DS.attr('string'),
    username: DS.attr('string'),
    isEmailVerified: DS.attr('boolean'),

    // Relationship [
    companies: DS.hasMany('company'),
    person: DS.belongsTo('person'),
    roles: DS.hasMany('role'),
    companyUsers: DS.hasMany('company-user'),
    // ]

    firstLetter: computed('name', function () {
        let name = this.get('name'); 

        if (isBlank(name)) {
            name = this.get('username');
        }

        return name.substring(0, 1);
    }),

    isNotMentor: computed('roles', function () {
        let roles = this.get('roles');

        let role_mentor = roles.filter(function (role) {
            return (role.get('name') == 'mentor');
        }).get('firstObject');

        return !isBlank(role_mentor);
    }),

    isStudent: computed('roles', function () {
        if (isBlank(this.get('roles'))) {
            return true;
        } else {
            return this.get('roles.length') <= 0;
        }
    }),

    isMentor: computed('roles', function () {
        let roles = this.get('roles');

        let roleMentor = roles.filter(function (role) {
            return (role.get('name') == 'mentor');
        }).get('firstObject');

        return !isBlank(roleMentor);
    }),

    primaryRoleName: computed('roles', function () {

        let primaryRole = this.get('roles.firstObject');

        if (!isBlank(primaryRole)) {
            return primaryRole.get('display_name');
        } else {
            return 'Student';
        }
    }),

    fullname: computed('person.fullname', 'name', function () {
        let fullname = this.get('person.fullname');

        if (isBlank(fullname)) {
            fullname = this.get('name');
        }

        return fullname;
    }),
});
