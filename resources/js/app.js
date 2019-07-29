
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import jquery from 'jquery';
import popper from 'popper.js';
import Vue from 'vue';
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)
import axios from 'axios';

import moment from 'moment';

import '@mdi/font/css/materialdesignicons.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'

Vue.component('moment', moment);
Vue.component('axios', axios);
Vue.component('stock', require('./components/stock.vue').default);

Vue.component('productos', require('./components/productos.vue').default);
Vue.component('proveedores', require('./components/proveedores').default);
Vue.component('clientes', require('./components/clientes').default);

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
        ['Proveedores','people_outline','/admin/proveedores'],
        ['Clientes','people','/admin/clientes']
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

