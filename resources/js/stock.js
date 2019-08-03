import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import store from './store/stockStore'
require('./bootstrap');
Vue.use(Vuetify)
Vue.component('stock', require('./components/stock.vue').default);

const app = new Vue({
    el: '#app',
    store,
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
    })
});

