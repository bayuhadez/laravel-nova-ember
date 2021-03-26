import Component from '@ember/component';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { tracked } from '@glimmer/tracking';

export default class MetronicPagesIndexPageComponent extends Component {
    // services
    @service store;
    @service router;

    @tracked addRoute = null;
    @tracked editRoute = null;

    constructor()
    {
        super(...arguments);
    }

    @action
    addRecord()
    {
        let route = this.addRoute;

        if (isBlank(route)) {
            route = this.get('baseRoute') + '.create';
        }
        this.get('router').transitionTo(route);
    }

    @action
    editRecord(record)
    {
        this.get('router').transitionTo(this.get('baseRoute') + '.edit', record.get('id'));
    }

    @action
    deleteRecord(record)
    {
        this
            .qswal
            .confirmDelete(() => {
                KTApp.blockPage();
                record
                    .destroyRecord()
                    .then(() => {
                        KTApp.unblockPage();
                        this.qswal.delete().s();
                    })
                    .catch(() => {
                        KTApp.unblockPage();
                        this.qswal.delete().e();
                    });
            });
    }
};
