import Vue from 'vue'
import moment from 'moment'
import numeral from 'numeral'

// axios
import './themeConfig';

import axios from "./axios.js"

Vue.prototype.$http = axios;

/*
validation
 */


/*
Configuration
 */
Vue.component('dashboard', require('./Components/dashboard-component.vue').default);
Vue.component('create-inventory', require('./Components/create-inventory.vue').default);
Vue.component('expiring-inventory', require('./Components/expiring-inventory.vue').default);
Vue.component('notification-component', require('./Components/notification-component.vue').default);

Vue.config.productionTip = false;


/*
Filters
 */
Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('MM/DD/YYYY hh:mm a')
    }
});

Vue.filter('formatYear', function(value) {
    if (value) {
        return moment(String(value)).format('YYYY/MM')
    }
});

Vue.filter("numeric", function (value) {
    return numeral(value).format("0,0"); // displaying other groupings/separators is possible, look at the docs
});

Vue.filter("decimal", function (value) {
    return numeral(value).format("0,0.00"); // displaying other groupings/separators is possible, look at the docs
});

Vue.filter('capitalize', function (value) {
    if (!value) return ''
    value = value.toString();
    return value.toUpperCase()
})

Vue.filter('percentage', function(value, decimals) {
    if(!value) {
        value = 0;
    }

    if(!decimals) {
        decimals = 0;
    }

    value = value * 100;
    value = Math.round(value * Math.pow(10, decimals)) / Math.pow(10, decimals);
    value = value + '%';
    return value;
});

Vue.directive('can', function (el, binding, vnode) {

    if(Permissions.indexOf(binding.value) !== -1){
        return vnode.elm.hidden = false;
    }else{
        return vnode.elm.hidden = true;
    }
})

const app = new Vue({
    el: '#app',
    model: 'history'
});
