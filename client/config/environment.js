'use strict';

module.exports = function(environment) {
  let ENV = {
    modulePrefix: 'rcf',
    environment,
    rootURL: '/',
    locationType: 'auto',
    EmberENV: {
      FEATURES: {
        // Here you can enable experimental features on an ember canary build
        // e.g. EMBER_NATIVE_DECORATOR_SUPPORT: true
      },
      EXTEND_PROTOTYPES: {
        // Prevent Ember Data from overriding Date.parse.
        Date: false
      }
    },

    // gReCaptcha
    gReCaptcha: {
      jsUrl: 'https://www.google.com/recaptcha/api.js?render=explicit',
      siteKey: '6LeUnacUAAAAAONmAqYQrOWhs9DQ1NhYamhpS21Z'
    },

    APP: {
      // Here you can pass flags/options to your application instance
      // when it is created
    },

    appUrl: process.env.APP_URL,
    apiNamespace: process.env.API_NAMESPACE,
    api2Namespace: process.env.API2_NAMESPACE,
    apiUrl: process.env.API_URL,
    socketUrl: process.env.SOCKET_URL,

    client_id: process.env.CLIENT_ID,
    client_secret: process.env.CLIENT_SECRET,

    midtransUrl: process.env.MIDTRANS_URL,
    midtransClietKey: process.env.MIDTRANS_CLIENT_KEY
  };

  ENV['ember-simple-auth'] = {
	  routeAfterAuthentication: '/'
  }

  ENV['ember-cli-mirage'] = {
    enabled: false,
    excludeFilesFromBuild: true
  };

  if (environment === 'development') {
	ENV.APP.TENANT = process.env.TENANT
    // ENV.APP.LOG_RESOLVER = true;
    // ENV.APP.LOG_ACTIVE_GENERATION = true;
    // ENV.APP.LOG_TRANSITIONS = true;
    // ENV.APP.LOG_TRANSITIONS_INTERNAL = true;
    // ENV.APP.LOG_VIEW_LOOKUPS = true;
  }

  if (environment === 'test') {
    // Testem prefers this...
    ENV.locationType = 'none';

    // keep test console output quieter
    ENV.APP.LOG_ACTIVE_GENERATION = false;
    ENV.APP.LOG_VIEW_LOOKUPS = false;

    ENV.APP.rootElement = '#ember-testing';
    ENV.APP.autoboot = false;
  }

  if (environment === 'production') {
	ENV.APP.TENANT = process.env.TENANT
    // here you can enable a production-specific feature
  }

  return ENV;
};
