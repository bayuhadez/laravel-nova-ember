import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { computed } from '@ember/object';
import dtc from 'rcf/utils/dtcolumn';
import { A } from '@ember/array';

export default Controller.extend({
    intl: service(),

    actions:{
        onSaveRecord()
        {
            let role = this.formRecord;
            let permissions = A();

            // get all permissions from checked abilities
            this.modules.forEach((module) => {
                for (var i = 0; i < module.abilities.length; i++) {
                    let ability = module.abilities[i];

                    if (ability.checked) {
                        permissions.addObject(ability.permission);
                    }
                }
            });

            role.set('permissions', permissions);
            return role.save();
        }
    },

    columns: computed(function () {
        return [
            dtc.create({
                name: this.intl.t('role.attr.name'),
                valuePath: 'name'
            }),
            dtc.create({
                buttons: [
                    {preset: 'edit'},
                ]
            }),
        ];
    }),
});
