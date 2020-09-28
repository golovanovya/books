require('./bootstrap');


window.Vue = require('vue');
import Vuetify from "../plugins/vuetify";

Vue.component('books-data-table', require('./components/BooksDataTable').default);
Vue.component('books-upload', require('./components/BooksUpload').default);

const app = new Vue({
    vuetify: Vuetify,
    el: '#app',
    data() {
        return {
            drawer: null
        };
    }
});
