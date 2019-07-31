<template>
    <v-card>
      <!--<v-card-title>
        Stock
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>-->
      <v-data-table
        :search="search"
        :headers="headers"
        :items="desserts"
        :options.sync="options"
        :server-items-length="totalDesserts"
        :loading="loading"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar flat>
          <v-toolbar-title>Proveedores</v-toolbar-title>
          <v-divider
            class="mx-4"
            inset
            vertical
          ></v-divider>
          <v-spacer></v-spacer>
          <v-text-field
          v-model="search"
          append-icon="search"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="500px">
              <template v-slot:activator="{ on }">
                <v-btn color="primary" dark class="mb-2" v-on:click="newItem()">New Item</v-btn>
              </template>
              <v-card  v-if="formCalc == false">
                <v-card-title>
                  <span class="headline">{{ formTitle }}</span>
                </v-card-title>
                <v-card-text>
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs18 sm9 md6>
                        <v-text-field v-model="selectedItem.nombre" label="Nombre" :rules="nombreRules"></v-text-field>
                      </v-flex>
                      <v-flex xs18 sm9 md6>
                        <v-text-field v-model="selectedItem.tel" label="Telefono"></v-text-field>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                  <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                </v-card-actions>
              </v-card>
              <v-card v-else>
                <v-card-title>
                  <span class="headline">¿Seguro que quiere eliminar el proveedor?</span>
                </v-card-title>
                <v-card-text>

                  <v-simple-table
                    :dense="dense"
                    :fixed-header="fixedHeader"
                    :height="height"
                  >
                    <tbody>
                      <tr>
                        <td>Marca:</td>
                        <td>{{ selectedItem.nombre }}</td>
                      </tr>
                      <tr>
                        <td>Modelo:</td>
                        <td>{{ selectedItem.tel }}</td>
                      </tr>
                      <!--<tr>
                        <td>Tipo de producto:</td>
                        <td>{{ selectedItem.tipo }}</td>
                      </tr>
                      <tr>
                        <td>Código de barras:</td>
                        <td>{{ selectedItem.codbarras }}</td>
                      </tr>-->
                    </tbody>
                  </v-simple-table>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                  <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                </v-card-actions>
              </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
    <template v-slot:item.action="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editItem(item)"
      >
        edit
      </v-icon>
      <v-icon
        small
        @click="deleteItem(item)"
      >
        delete
      </v-icon>
    </template>
    <!--<template v-slot:no-data>
      <v-btn color="primary" @click="initialize">Reset</v-btn>
    </template>-->
      </v-data-table>
      <!--<v-btn
        dark
        color="red darken-2"
        @click="snackbar.active = true"
      >
        Open Snackbar
      </v-btn>-->
      <v-snackbar
      v-model="snackbar.active"
      :color="snackbar.color"
      :multiLine="snackbar.multiline"
      >
      <v-icon>
        {{snackbar.icon}}
      </v-icon>
      <strong>{{snackbar.title}}</strong>
      
      {{snackbar.text}}
        <v-btn
          color="black"
          text
          @click="snackbar.active = false"
        >
          Close
        </v-btn>
      </v-snackbar>
    </v-card>
</template>
<script>
  export default {
    data () {
      return {
        nombreRules: [
          v => !!v || 'Este campo es requerido'
        ],
        snackbar: 
        {
          active: false,
          text: null,
          color: "success",
          multiline: true,
          icon: null,
          title: null
        },
        formTitle: "",
        comboboxes:
        {
          fields:{
            tipos: [],
            marcas: []  
          },
          searching:{
            tipo: "",
            marca: ""
          }
        },
        dialog: false,
        search:'',
        totalDesserts: 0,
        desserts: [],
        loading: true,
        options: {},
        headers: [
          { text: 'Nombre', value: 'nombre' },
          { text: 'Telefono', value: 'tel' },
          { text: 'Actions', value: 'action', sortable: false },
        ],
        //EXPERIMENTAL
        defaultItem: {
          id: null,
          nombre: null,
          tel: null
        },
        selectedItem: {
          id: 0,
          nombre: null,
          tel: null
        }
      }
    },
    computed:
    {
      dataForPost: function()
      {
        var post = {}
        Object.entries(this.selectedItem).forEach(function(value,key)
        {
          if(value[1] != null && value[1] != "")
          {
            post[value[0]] = value[1]  
          }
          
        });
        return post
      },
      formCalc: function()
      {
        if(this.formTitle == 'Eliminar proveedor')
        {
          return true
        }
        else
        {
          return false
        }

      }
    },
    watch: {
      search:{
        handler () {
          this.getDataFromApi()
        },
        deep: true,
      },
      options: {
        handler () {
          this.getDataFromApi()
        },
        deep: true,
      },
      dialog (val) {
        val || this.close()
      },
    },
    mounted () {
      this.getDataFromApi()
      this.cargarSelects()
    },
    methods: {
      getDataFromApi () {
        this.loading = true
          const { sortBy, descending, page, itemsPerPage, sortDesc } = this.options
          axios.post('/datatables/getproveedores',
          {
            search: this.search,
            sortBy: this.options.sortBy,
            sortDesc: this.options.sortDesc,
            descending: this.options.descending,
            page: this.options.page,
            itemsPerPage: this.options.itemsPerPage
          }).then(response => {
            this.desserts = response.data.data
            this.totalDesserts = response.data.total
            this.loading = false;
          });
      },
      newItem ()
      {
        this.formTitle = "Nuevo proveedor"
        this.selectedItem = Object.assign({}, this.defaultItem)
        this.dialog = true  
      },
      editItem (item) {
        this.formTitle = "Editar proveedor"
        this.selectedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        this.formTitle = "Eliminar proveedor"
        this.selectedItem = Object.assign({}, item)
        this.dialog = true
      },

      close () {
        this.dialog = false
        setTimeout(() => {
          this.selectedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
          this.getDataFromApi();
        }, 300)
      },

      save () {
        if(this.formTitle == "Nuevo proveedor")
        {
          axios.post('/admin/proveedores/nuevo',
                this.dataForPost).then(response => {
                  this.snackbar.color = "rgba(0,255,0,0.5)";
                  this.snackbar.text = " Proveedor creado correctamente.";
                  this.snackbar.title="Exito:";
                  this.snackbar.icon = "done";
                  this.snackbar.active = true;
                }).catch(error => {
                  this.snackbar.color = "rgba(255,0,0,0.5)"
                  this.snackbar.title = "ERROR:";
                  this.snackbar.text = "El Proveedor ya existe!"
                  this.snackbar.icon = "report_problem"
                  this.snackbar.active = true;
                });
        }
        else if(this.formTitle == "Editar proveedor")
        {
          axios.post('/admin/proveedores/editar',
                this.selectedItem).then(response => {
                  
                });
        }
        else if(this.formTitle == "Eliminar proveedor")
        {
          axios.post('/admin/proveedores/eliminar',
                {id:this.selectedItem.id}).then(response => {
                    
                });   
        }
        this.close()
      },
      //CARGAR COMBOBOX
      cargarSelects: function()
      {
        this.getTiposProductos();
        this.getMarcas();
      },
      getTiposProductos: function()
      {
          axios.get('/ajax/tiposprods')
            .then(response => {
              this.comboboxes.fields.tipos = response.data;
          });
      },
      getMarcas: function()
      {
        axios.get('/ajax/marcas')
          .then(response => {
            this.comboboxes.fields.marcas = response.data;    
        });
      },
    
    },
  }

</script>