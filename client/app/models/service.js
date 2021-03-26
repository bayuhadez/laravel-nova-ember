import BaseModel from 'rcf/models/base-model';
import {attr, belongsTo, hasMany} from '@ember-data/model';
import { isBlank } from '@ember/utils'

export default class ServiceModel extends BaseModel {
    @attr('string') code;
    @attr('string') name;
    @attr('string') description;
    @attr('number') price;

    //relationships
    @belongsTo('service-category') serviceCategory;
    @hasMany('company-service') companyServices;

    async saveWithRelations()
    {
        // save service
        await this.save();

        // save companyServices
        let css = await this.companyServices;
        css.forEach(async (cs) => {
            await cs.save();
        });
    }

    get displayName()
    {
        return this.code + ' - ' + this.name;
    }

    /**
     * if no company provided, returns service.price. otherwise returns
     * company_service.price for this service and provided company
     *
     * @param Company the company model to be used to search for related
     * company_service record
     * @return float
     */
    getSellPrice(company) {
        var sellPrice;

        if (!isBlank(company)) {
            let cs = this.companyServices.findBy('company.id', company.id);

            if (!isBlank(cs)) {
                sellPrice = cs.price;
            } else {
                sellPrice = this.price;
            }

        } else {
            sellPrice = this.price;
        }

        return sellPrice;
    }
}
