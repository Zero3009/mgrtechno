
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vuelidate from 'vuelidate'
//import { required, maxLength, email } from 'vuelidate/lib/validators'
import jquery from 'jquery';
import popper from 'popper.js';
import Vue from 'vue';
//import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)
Vue.use(Vuelidate)
//import Datepicker from 'vuejs-datepicker';
import axios from 'axios';
//import vuetable from 'vuetable-2';
//import VuetablePagination from 'vuetable-2/src/components/VuetablePagination';
import moment from 'moment';
//import BootstrapVue from 'bootstrap-vue';
//import 'es6-promise/auto';
//import Vuex from 'vuex';
//import FilterBar from './components/FilterBar';
//import VueEvents from 'vue-events'
//import CustomActionsProductos from './components/tables/actions/CustomActionsProductos'
//import CustomActionsStock from './components/tables/actions/CustomActionsStock'
import '@mdi/font/css/materialdesignicons.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
//import VueToastr from '@deveodk/vue-toastr'
//import '@deveodk/vue-toastr/dist/@deveodk/vue-toastr.css'

//Vue.component('custom-actions-productos', CustomActionsProductos)
//Vue.component('custom-actions-stock', CustomActionsStock)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//Vue.use(BootstrapVue);
//Vue.use(VueEvents);
Vue.component('moment', moment);
//Vue.component('v-select', vSelect);
Vue.component('axios', axios);
Vue.component('app', require('./components/app.vue').default);
//Vue.component('vuetable', vuetable);
//Vue.component('vuetable-pagination', VuetablePagination);
//Vue.component('vuejs-datepicker', Datepicker);
//Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('stock', require('./components/stock.vue').default);
/*Vue.component('stocktable', require('./components/tables/stocktable.vue').default);
*///
Vue.component('productos', require('./components/productos.vue').default);
//Vue.component('productosnuevo', require('./components/productosnuevo.vue'));
Vue.component('proveedores', require('./components/proveedores').default);
/*Vue.component('filter-bar', FilterBar);*/
//Vue.use(VueToastr)

/*const store = new Vuex.Store({
  state: {
    options: [],
    serials: [],
    proveedores: []
  },
  mutations: {
    setOptions (state, optionss) {
    	state.options = optionss;
    },
    setSerials (state, serialss) {
      state.serials = serialss;
    },
    setProvs (state, provss) {
      state.proveedores = provss;
    }
  }
});*/
/*const store = new Vuex.Store({
  state: {
    options: [],
    serials: [],
    proveedores: []
  },
  mutations: {
    setOptions (state, optionss) {
      state.options = optionss;
    },
    setSerials (state, serialss) {
      state.serials = serialss;
    },
    setProvs (state, provss) {
      state.proveedores = provss;
    }
  }
});*/

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify({
      theme: { dark: true },
    }),
      data: () => ({
      drawer: null,
      admins: [
        ['Management', 'people_outline'],
        ['Settings', 'settings'],
      ],
      stock: [
        ['Alta','add','/admin/stock'],
        ['Recuperar', 'restore_from_trash','/admin']
      ],
      settings:[
        ['Productos', 'add_circle','/admin/productos'],
        ['Proveedores','people_outline','/admin/proveedores']
      ],
      cruds: [
        ['Create', 'add'],
        ['Read', 'insert_drive_file'],
        ['Update', 'update'],
        ['Delete', 'delete'],
      ],
    })/*,
    computed: 
    {
    	optionsGlobals()
    	{
    		return store.state.options;
    	},
      serialsGlobals()
      {
        return store.state.serials;
      },
      provsGlobals()
      {
        return store.state.proveedores;
      }
    },
    methods:
    {
    	setGlobalOptions(ops)
    	{
    		store.commit('setOptions', ops);
    	},
      setGlobalSerials(ops)
      {
        store.commit('setSerials', ops);
      },
      setGlobalProvs(ops)
      {
        store.commit('setProvs', ops);
      },
	  
    }*/
});

