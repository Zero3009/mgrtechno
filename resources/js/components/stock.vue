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
          <v-toolbar-title>Productos</v-toolbar-title>
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
                  <span class="headline">{{ formTitle + ': ' + selectedItem.codbarras.modelo}}</span>
                </v-card-title>
                <v-card-text>
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs12 sm6 md4>
                        <!--<v-text-field v-model="selectedItem.codbarras" label="Código de barras"></v-text-field>-->
                        <v-combobox
                          v-model="selectedItem.codbarras"
                          :items="comboboxes.fields.codbarras"
                          :search-input.sync="comboboxes.searching.codbarras"
                          hide-selected
                          hint="Seleccione la marca, si no existe escribala"
                          label="Código de barras"
                          persistent-hint
                        >
                          <template v-slot:no-data>
                            <v-list-item>
                              <v-list-item-content>
                                <v-list-item-title>
                                  No se encontraron resultados para "<strong>{{ comboboxes.searching.tipo }}</strong>". Presiona <kbd>enter</kbd> para crearlo
                                </v-list-item-title>
                              </v-list-item-content>
                            </v-list-item>
                          </template>
                        </v-combobox>
                      </v-flex>
                      <v-flex xs12 sm6 md4>
                        <!--<v-text-field v-model="selectedItem.tipo" label="Tipo de producto"></v-text-field>-->
                        <v-combobox
                          v-model="selectedItem.proveedor"
                          :items="comboboxes.fields.proveedores"
                          :search-input.sync="comboboxes.searching.proveedor"
                          hide-selected
                          hint="Seleccione la marca, si no existe escribala"
                          label="Proveedor"
                          persistent-hint
                        >
                          <template v-slot:no-data>
                            <v-list-item>
                              <v-list-item-content>
                                <v-list-item-title>
                                  No se encontraron resultados para "<strong>{{ comboboxes.searching.tipo }}</strong>". Presiona <kbd>enter</kbd> para crearlo
                                </v-list-item-title>
                              </v-list-item-content>
                            </v-list-item>
                          </template>
                        </v-combobox>
                      </v-flex>
                      <v-flex xs12 sm6 md4>
                        <v-menu
                          ref="menu"
                          v-model="menu"
                          :close-on-content-click="false"
                          :return-value.sync="selectedItem.fecha_entrada"
                          transition="scale-transition"
                          offset-y
                          full-width
                          min-width="290px"
                        >
                          <template v-slot:activator="{ on }">
                            <v-text-field
                              v-model="selectedItem.fecha_entrada"
                              label="Picker in menu"
                              prepend-icon="event"
                              readonly
                              v-on="on"
                            ></v-text-field>
                          </template>
                          <v-date-picker v-model="selectedItem.fecha_entrada" no-title scrollable>
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                            <v-btn text color="primary" @click="$refs.menu.save(selectedItem.fecha_entrada)">OK</v-btn>
                          </v-date-picker>
                        </v-menu>
                        <!--<v-text-field v-model="selectedItem.marca" label="Marca"></v-text-field>-->
                      </v-flex>
                      <v-flex xs12 sm6 md4>
                        <v-combobox
                          v-model="selectedItem.seriales"
                          :items="comboboxes.fields.seriales"
                          :search-input.sync="comboboxes.searching.seriales"
                          hide-selected
                          small-chips
                          multiple
                          hint="Introduzca todos los seriales del mismo producto"
                          label="Seriales"
                          persistent-hint
                        >
                          <template v-slot:no-data>
                            <v-list-item>
                              <v-list-item-content>
                                <v-list-item-title>
                                  No se encontraron resultados para "<strong>{{ comboboxes.searching.tipo }}</strong>". Presiona <kbd>enter</kbd> para crearlo
                                </v-list-item-title>
                              </v-list-item-content>
                            </v-list-item>
                          </template>
                        </v-combobox>
                        <!--<v-text-field v-model="selectedItem.modelo" label="Modelo"></v-text-field>-->
                      </v-flex>
                      <v-flex xs12 sm6 md4>
                        <v-text-field v-model="selectedItem.precio_entrada" type="numeric" label="Precio de entrada"></v-text-field>  
                      </v-flex>
                      <!--<v-flex xs12 sm6 md4>
                        <v-text-field v-model="editedItem.protein" label="Protein (g)"></v-text-field>
                      </v-flex>-->
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
                  <span class="headline">¿Seguro que quiere eliminar el producto?</span>
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
                        <td>{{ selectedItem.marca }}</td>
                      </tr>
                      <tr>
                        <td>Modelo:</td>
                        <td>{{ selectedItem.modelo }}</td>
                      </tr>
                      <tr>
                        <td>Tipo de producto:</td>
                        <td>{{ selectedItem.tipo }}</td>
                      </tr>
                      <tr>
                        <td>Código de barras:</td>
                        <td>{{ selectedItem.codbarras }}</td>
                      </tr>
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
    </template><!--
    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize">Reset</v-btn>
    </template>-->
      </v-data-table>
    </v-card>
</template>
<script>
  export default {
    data () {
      return {
        formTitle: "",
        menu: false,
        comboboxes:
        {
          fields:{
            codbarras: [],
            tipos: [],
            marcas: [],
            proveedores: [],
            seriales: []
          },
          searching:{
            codbarras: "",
            tipo: "",
            marca: "",
            proveedor: "",
            seriales: ""
          }
        },
        dialog: false,
        search:'',
        totalDesserts: 0,
        desserts: [],
        loading: true,
        options: {},
        headers: [
          { text: 'ID', value:'id'},
          { text: 'Código de barras', value: 'codbarras' },
          { text: 'Tipo de producto', value: 'tipo' },
          { text: 'Modelo', value: 'modelo' },
          { text: 'Marca', value: 'marca' },
          { text: 'Serial', value: 'serial'},
          { text: 'Fecha de entrada', value: 'fecha_entrada'},
          { text: 'Precio de entrada', value: 'precio_entrada'},
          { text: 'Actions', value: 'action', sortable: false }
        ],
        //EXPERIMENTAL
        editedIndex: -1,
        editedItem: {
          name: '',
          calories: 0,
          fat: 0,
          carbs: 0,
          protein: 0,
        },
        defaultItem: {
          id: 0,
          codbarras: {
            value: 0,
            text: "",
            modelo: ""
          },
          tipo: "",
          marca: "",
          modelo: "",
          proveedor: "",
          fecha_entrada: "",
          precio_entrada: 0,
          seriales: []
        },
        selectedItem: {
          id: 0,
          codbarras: {
            value: 0,
            text: "",
            modelo: ""
          },
          tipo: "",
          marca: "",
          modelo: "",
          proveedor: "",
          fecha_entrada: "",
          precio_entrada: 0,
          seriales: []
        }
      }
    },
    computed:
    {
      formCalc: function()
      {
        if(this.formTitle == 'Eliminar producto')
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
          const { sortBy, descending, page, itemsPerPage } = this.options
          axios.post('/datatables/getstock',
          {
            search: this.search,
            sortBy: this.options.sortBy,
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
        this.formTitle = "Nuevo producto"
        this.selectedItem = Object.assign({}, this.defaultItem)
        this.dialog = true  
      },
      editItem (item) {
        this.formTitle = "Editar producto"
        this.selectedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        this.formTitle = "Eliminar producto"
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
        if(this.formTitle == "Nuevo producto")
        {
          axios.post('/admin/stock/nuevo',
                this.selectedItem).then(response => {
                  
                });
        }
        else if(this.formTitle == "Editar producto")
        {
          axios.post('/admin/productos/editar',
                this.selectedItem).then(response => {
                  
                });
        }
        else if(this.formTitle == "Eliminar producto")
        {
          axios.post('/admin/productos/eliminar',
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
        this.getCodbarras();
        this.getProveedores();
        this.getSeriales();
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
      getCodbarras()
      {
        axios.get('/ajax/codbarras')
            .then(response => {
              this.comboboxes.fields.codbarras = response.data;
        });
      },
      getProveedores()
      {
          axios.get('/ajax/proveedores')
              .then(response => {
                  
                  this.comboboxes.fields.proveedores = response.data;
                  //this.$parent.$options.methods.setGlobalProvs(response.data);

          });
      },
      getSeriales()
      {
        axios.get('/ajax/seriales')
            .then(response => {
              var a = [];
              console.log(response.data)
              response.data.forEach(function(item){
                            a.push({
                              value: item.value,
                              disabled: true,
                              text: item.text
                            });
              }); 
              this.comboboxes.fields.seriales = a;
            
            });
      }
    
    },
  }

</script>