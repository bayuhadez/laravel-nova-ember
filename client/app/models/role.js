import Model from '@ember-data/model';
import { attr } from '@ember-data/model';
import { hasMany } from '@ember-data/model';

export default Model.extend({
    name: attr('string'),
    display_name: attr('string'),
    description: attr('string'),

    users: hasMany('user', { inverse: 'roles' }),
    permissions: hasMany(),
});
