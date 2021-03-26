import ApplicationAdapter from './application';

export default ApplicationAdapter.extend({
    urlForQueryRecord(query) {
        if (query.current) {
            delete query.current;
            return `${this._super(...arguments)}/current`;
        }
        
        return this._super(...arguments);
    }
});