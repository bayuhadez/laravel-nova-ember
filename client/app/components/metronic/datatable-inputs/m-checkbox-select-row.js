import Component from '@glimmer/component';
import { action, computed } from '@ember/object';
import { tracked } from '@glimmer/tracking';
import { isBlank } from '@ember/utils';

export default class MetronicDatatableInputsMCheckboxSelectRowComponent extends Component {

    constructor()
    {
        super(...arguments);
    }

    @computed('args.checkedRows.[]', 'args.record')
    get isChecked()
    {
        let record = this.args.checkedRows.findBy(
            this.args.targetAttr ?? 'id',
            this.args.record.get(this.args.targetAttr ?? 'id')
        );

        return !isBlank(record);
    }

    @action
    toggleChecked(e)
    {
        if(this.args.changeAction){
            this.args.changeAction(this.args.record, e);
        } else {
            if (e.target.checked) {
                this.args.checkedRows.pushObject(this.args.record);
            } else {
                this.args.checkedRows.removeObject(this.args.record);
            }
        }
    }
}
