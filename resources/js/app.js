
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('bootstrap-select');
require('bootstrap-datepicker');

window.Vue = require('vue');
Vue.component('modal', require('./components/Modal.vue').default);
Vue.component('datepicker', require('./components/DatePicker.vue').default);
Vue.component('form-error', require('./components/FormError.vue').default);
Vue.component('money', require('./components/Money.vue').default);
Vue.component('form-error', require('./components/FormError.vue').default);
Vue.component('paginador', require('./components/Paginador.vue').default);
Vue.component('cobro', require('./components/Cobro.vue').default);
Vue.component('cobro_compra_coins', require('./components/CobroCompraCoins.vue').default);
Vue.component('cobro_compra_coins_dos', require('./components/CobroCompraCoinsDos.vue').default);
Vue.component('fecha', require('./components/Fecha.vue').default);
Vue.component('img_dia', require('./components/DiaImagen.vue').default);
import { ToggleButton } from 'vue-js-toggle-button'
import VueAnimate from 'vue-animate-scroll'
import wysiwyg from "vue-wysiwyg";
import captcha from "vue-recaptcha";
import Vue from 'vue'
import VTooltip from 'v-tooltip'
import VueRx from 'vue-rx'
import VuejsClipper from 'vuejs-clipper'
// install vue-rx
Vue.use(VueRx)
// install vuejs-clipper
Vue.use(VuejsClipper)

Vue.use(VuejsClipper, {
    components: {
        clipperBasic: true,
        clipperPreview: true
    }
})

Vue.use(VueRx);
Vue.use(VuejsClipper);
Vue.component('ToggleButton', ToggleButton);
Vue.component('captcha', captcha);
Vue.use(VueAnimate);
Vue.use(wysiwyg,{hideModules: { "image": true }});
Vue.use(VTooltip)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app'
// });
