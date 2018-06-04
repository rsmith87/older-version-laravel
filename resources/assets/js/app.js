
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


//import Icon from 'vue-awesome/components/Icon'

// globally (in your main .js file)
//Vue.component('icon', Icon)
Vue.component('timer', require('./components/TimerComponent.vue'));
const app = new Vue({
    el: '#app'
});
