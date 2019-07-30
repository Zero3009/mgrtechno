import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

require('./bootstrap');
Vue.use(Vuetify)
Vue.component('productos', require('./components/productos.vue').default);

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
    })
});

