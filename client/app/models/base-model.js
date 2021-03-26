import Model from '@ember-data/model';
import { isBlank } from '@ember/utils';

export default class BaseModel extends Model {

    rollbackRelationships()
    {
        return new Promise(resolve => {
            this.eachRelationship((name, descriptor) => {
                this.get(name).then((rel) => {
                    if (!isBlank(rel)) {
                        if (descriptor.kind === 'hasMany') {
                            rel.toArray().forEach((item) => {
                                item.rollbackAttributes();
                            });
                        } else if (descriptor.kind === 'belongsTo') {
                            rel.rollbackAttributes();
                        }
                    }
                    resolve();
                });
            });
        });
    }

}
