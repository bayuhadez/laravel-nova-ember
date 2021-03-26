import Component from '@glimmer/component';
import dtc from 'rcf/utils/dtcolumn';
import { A } from '@ember/array';
import { computed, set } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { tracked } from '@glimmer/tracking';

export default class MetronicFormsMProductPlacementComponent extends Component {
    @service store;

    @tracked keyInProductStockMovement = 0;
    @tracked currentStepId;
    @tracked refreshData = false;

    //company;
    steps = A();
    allowedSteps = A();

    constructor()
    {
        super(...arguments);

        this.steps = A([
            {id: 0, name: "OUT"},
            {id: 1, name: "IN"},
        ]);

        this.currentStepId = this.args.currentStepId || 0;

        let skippedSteps = this.args.skippedSteps || A();
        if (isBlank(this.args.skippedSteps)) {
            this.allowedSteps = this.steps;
        } else {
            this.allowedSteps = this.steps.filter((step) => {
                return !this.args.skippedSteps.includes(step.id);
            });
        }

        this.currentStepId = this.allowedSteps.objectAt(0).id;
        this.stockDivisions = this.args.company.get('stockDivisions');
        this.warehouseOptions = this.args.company.get('warehouses');
    }

    // --- Methods: ---
    setNextStep() {
        let nextStep = this.allowedSteps.find(step => {
            return step.id > this.currentStepId;
        });

        if (nextStep !== undefined) {
            this.currentStepId = nextStep.id;
        } else {
            this.currentStepId = null;
        }
    }
}
