import Route from '@ember/routing/route';
import { A } from '@ember/array';
import { isBlank } from '@ember/utils'
import EmberObject from '@ember/object';

export default Route.extend({
    model()
    {
        return this.store.findAll('permission');
    },

    setupController(controller, model)
    {
        let permissions = model;
        let modules = A();

        permissions.forEach((permission) => {
            let module;

            module = modules.findBy('name', permission.get('module'));

            if (isBlank(module)) {
                module = {
                    name: permission.get('module'),
                    abilities: [],
                };

                modules.addObject(module);
            }

            module.abilities.addObject(EmberObject.create({
                name: permission.get('ability'),
                checked: false,
                permission: permission,
            }));
        });

        controller.setProperties({
            modules: modules
        });
    },
});
