import Mirage from 'ember-cli-mirage';
import ENV from 'rcf/config/environment';

export default function() {

	// These comments are here to help you get started. Feel free to delete them.

	/*
	Config (with defaults).

	Note: these only affect routes defined *after* them!
	*/

	this.passthrough('/assets/particles.json');
	this.passthrough(ENV.socketUrl+'/**');

	this.urlPrefix = ENV.apiUrl;
	this.namespace = '/api/v1';    // make this `/api`, for example, if your API is namespaced
	// this.timing = 400;      // delay for each request, automatically set to 0 during testing
	// this.pretender.get('/assets/*passthrough', this.pretender.passthrough);


	/*
	Shorthand cheatsheet:

	this.get('/posts');
	this.post('/posts');
	this.get('/posts/:id');
	this.put('/posts/:id'); // or this.patch
	this.del('/posts/:id');

	http://www.ember-cli-mirage.com/docs/v0.3.x/shorthands/
	*/

	this.get('/product-categories', (schema) => {
		return schema.productCategories.all();
    });

    function formEncodedToJson(encoded) {
        var result = {};
        encoded.split("&").forEach(function(part) {
          var item = part.split("=");
          result[item[0]] = decodeURIComponent(item[1]);
        });
        return result;
    }

    this.post('/token', (db, request) => {
        var params = formEncodedToJson(request.requestBody);
        if (params.username == 'login@mail' && params.password == '1') {
            return new Mirage.Response(201, {}, {
                access_token:"PA$$WORD",
                token_type:"bearer"
            });
        } else {
            return new Mirage.Response(400, {}, {responseText: 'Your Auth Credential is Invalid'});
        }
    });

    this.get('/users', (schema) => {
        return schema.users.all();
    });

    this.get('/users/current', (db, request) => {
        if (request.requestHeaders.Authorization === "Bearer PA$$WORD") {
            return { 
                data: {
                    id: '1',
                    type: 'users',
                    attributes: { email: 'login@mail', name: 'Prabowo Subianto' }
                }
            }
        } else {
            return new Mirage.Response(401, {}, {});
        }
    });

    this.post('/users', (db, request) => {
        var params = formEncodedToJson(request.requestBody);
        if (params.name || params.email || params.password || params.password_confirmation) {
            return new Mirage.Response(401,{}, { error: 'Hiya Hiya Hiya'});
        } 
        const attrs = JSON.parse(request.requestBody).user;
        return db.user.create(attrs);
    });

    this.get('/users/:id')

	this.get('/products/:id')

}
