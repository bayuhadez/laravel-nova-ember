module.exports = function(env) {
	return {
		clientAllowedKeys: [
			'APP_URL',
			'API_NAMESPACE',
			'API_URL',
            'SOCKET_URL'
		],
		failOnMissingKey: true,
	};
};
