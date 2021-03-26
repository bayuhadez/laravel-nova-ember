import { Factory, faker } from 'ember-cli-mirage';

export default Factory.extend({
	name()
	{
		return faker.commerce.productName();
	},
	thumbnailUrl()
	{
		return faker.image.cats();
	},
	price()
	{
		return faker.commerce.price();
	},
	hintText()
	{
		return faker.name.firstName();
	}
});
