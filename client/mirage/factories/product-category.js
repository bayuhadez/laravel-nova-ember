import { Factory, faker } from 'ember-cli-mirage';

export default Factory.extend({
	name()
	{
		return faker.commerce.department();
	},

	afterCreate(productCategory, server)
	{
		server.createList('product', 5, { productCategory });
	}
});
