Nova.booting((Vue, router) => {
    Vue.component('index-name', require('./components/IndexField'));
    Vue.component('detail-name', require('./components/DetailField'));
    Vue.component('form-name', require('./components/FormField'));
})
