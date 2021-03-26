import Component from '@ember/component';
import { inject as service } from '@ember/service';

export default Component.extend({
    store: service(),

    didReceiveAttrs()
    {
        let role = this.formRecord;

        this.modules.forEach((module) => {
            module.abilities.forEach((ability) => {
                let found = false;

                role.get('permissions').forEach((permission) => {
                    if (permission.get('id') === ability.permission.get('id')) {
                        ability.set('checked', true);
                        found = true;
                    }
                });

                if (!found) {
                    ability.set('checked', false);
                }

            });
        });
    }
});
