<template>
    <v-card>
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
                  <span class="headline">{{ fullTitle }}</span>
                </v-card-title>
                  <v-form
                    ref="form"
                    v-model="valid"
                  >
                    <!--<v-text-field
                      v-model="name"
                      :counter="10"
                      :rules="nameRules"
                      label="Name"
                      required
                    ></v-text-field>-->
                    <v-card-text>
                      <v-container grid-list-md>
                        <v-layout wrap>
                          <v-flex xs18 sm9 md6>
                            <v-combobox
                                v-model="selectedItem.codbarras"
                                :items="comboboxes.fields.codbarras"
                                :search-input.sync="comboboxes.searching.codbarras"
                                hide-selected
                                hint="Seleccione la marca, si no existe escribala"
                                label="Código de barras"
                                persistent-hint
                                required
                                :rules="codbarrasRules"
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
                            <v-flex xs18 sm9 md6>
                              <v-combobox
                                v-model="selectedItem.proveedor"
                                :items="comboboxes.fields.proveedores"
                                :search-input.sync="comboboxes.searching.proveedor"
                                hide-selected
                                hint="Seleccione la marca, si no existe escribala"
                                label="Proveedor"
                                persistent-hint
                                :rules="proveedorRules"
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
                            <v-flex xs18 sm9 md6>
                          <v-menu
                            ref="fecha_entrada"
                            v-model="fecha_entrada"
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
                                label="Fecha de entrada"
                                prepend-icon="event"
                                readonly
                                v-on="on"
                                :rules="fecha_entradaRules"
                              ></v-text-field>
                            </template>
                            <v-date-picker locale="es-419" v-model="selectedItem.fecha_entrada" no-title scrollable>
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="fecha_entrada = false">Cancel</v-btn>
                              <v-btn text color="primary" @click="$refs.fecha_entrada.save(selectedItem.fecha_entrada)">OK</v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-flex>
                        <v-flex xs18 sm9 md6>
                          <v-text-field v-model="selectedItem.precio_entrada" :rules="precio_entradaRules" type="numeric" label="Precio de entrada" required></v-text-field>  
                        </v-flex>
                        <v-flex xs18 sm9 md6>
                          <v-menu
                            ref="fecha_salida"
                            v-model="fecha_salida"
                            :close-on-content-click="false"
                            :return-value.sync="selectedItem.fecha_salida"
                            transition="scale-transition"
                            offset-y
                            full-width
                            min-width="290px"
                          >
                            <template v-slot:activator="{ on }">
                              <v-text-field
                                v-model="selectedItem.fecha_salida"
                                label="Fecha de salida"
                                prepend-icon="event"
                                readonly
                                v-on="on"
                              ></v-text-field>
                            </template>
                            <v-date-picker  locale="es-419" v-model="selectedItem.fecha_salida" no-title scrollable>
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="fecha_salida = false">Cancel</v-btn>
                              <v-btn text color="primary" @click="$refs.fecha_salida.save(selectedItem.fecha_salida)">OK</v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-flex>
                        <v-flex xs18 sm9 md6>
                          <v-text-field v-model="selectedItem.precio_salida" type="numeric" label="Precio de salida" required></v-text-field>  
                        </v-flex>
                        <v-flex xs36 sm18 md12 v-if="formTitle == 'Nuevo producto'">
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
                            :rules="serialesRules"
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
                        <v-flex xs36 sm18 md12 v-if="formTitle == 'Editar producto'">
                          <v-combobox
                            v-model="selectedItem.serial"
                            :items="comboboxes.fields.seriales"
                            :search-input.sync="comboboxes.searching.seriales"
                            hide-selected
                            hint="Introduzca todos los seriales del mismo producto"
                            label="Seriales"
                            persistent-hint
                            :rules="serialesRules"
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
                        
                        
                          </v-layout>
                        </v-container>
                      </v-card-text>
                <!--<v-form ref="form" v-model="valid" lazy-validation>
                  <v-card-text>
                    <v-container grid-list-md>
                      <v-layout wrap>
                        <v-flex xs12 sm6 md4>
                          <v-combobox
                            v-model="selectedItem.codbarras"
                            :items="comboboxes.fields.codbarras"
                            :search-input.sync="comboboxes.searching.codbarras"
                            hide-selected
                            hint="Seleccione la marca, si no existe escribala"
                            label="Código de barras"
                            persistent-hint
                            required
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
                            ref="fecha_entrada"
                            v-model="fecha_entrada"
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
                                label="Fecha de entrada"
                                prepend-icon="event"
                                readonly
                                v-on="on"
                              ></v-text-field>
                            </template>
                            <v-date-picker locale="es-419" v-model="selectedItem.fecha_entrada" no-title scrollable>
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="fecha_entrada = false">Cancel</v-btn>
                              <v-btn text color="primary" @click="$refs.fecha_entrada.save(selectedItem.fecha_entrada)">OK</v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                          <v-menu
                            ref="fecha_salida"
                            v-model="fecha_salida"
                            :close-on-content-click="false"
                            :return-value.sync="selectedItem.fecha_salida"
                            transition="scale-transition"
                            offset-y
                            full-width
                            min-width="290px"
                          >
                            <template v-slot:activator="{ on }">
                              <v-text-field
                                v-model="selectedItem.fecha_salida"
                                label="Fecha de salida"
                                prepend-icon="event"
                                readonly
                                v-on="on"
                              ></v-text-field>
                            </template>
                            <v-date-picker locale="es-419" v-model="selectedItem.fecha_salida" no-title scrollable>
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="fecha_salida = false">Cancel</v-btn>
                              <v-btn text color="primary" @click="$refs.fecha_salida.save(selectedItem.fecha_entrada)">OK</v-btn>
                            </v-date-picker>
                          </v-menu>
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
                          <v-text-field v-model="selectedItem.modelo" label="Modelo"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                          <v-text-field v-model="selectedItem.precio_entrada" type="numeric" label="Precio de entrada" required></v-text-field>  
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                          <v-text-field v-model="editedItem.protein" label="Protein (g)"></v-text-field>
                        </v-flex>
                      </v-layout>
                    </v-container>
                  </v-card-text>
-->
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                    <v-btn :disabled="!valid" color="success" text @click="save">Save</v-btn>
                  </v-card-actions>
                </v-form>
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
                        <td>Serial:</td>
                        <td>{{ selectedItem.serial }}</td>
                      </tr>
                      <tr>
                        <td>Tipo de producto:</td>
                        <td>{{ selectedItem.tipo }}</td>
                      </tr>
                      <tr>
                        <td>Código de barras:</td>
                        <td>{{ selectedItem.codbarras }}</td>
                      </tr>
                      <tr>
                        <td>Código de barras:</td>
                        <td>{{ selectedItem.proveedor}}</td>
                      </tr>
                      <tr>
                        <td>Código de barras:</td>
                        <td>{{ selectedItem.fecha_entrada }}</td>
                      </tr>
                      <tr>
                        <td>Código de barras:</td>
                        <td>{{ selectedItem.precio_entrada }}</td>
                      </tr>
                      <tr>
                        <td>Código de barras:</td>
                        <td>{{ selectedItem.fecha_salida }}</td>
                      </tr>
                      <tr>
                        <td>Código de barras:</td>
                        <td>{{ selectedItem.precio_salida }}</td>
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
        done
      </v-icon>
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
        serialesRules: [
          v => !!v || 'Debe ingresar al menos un serial'
        ],
        codbarrasRules: [
          v => !!v || 'Código de barras requerido',
          //v => (v && v.length <= 10) || 'Name must be less than 10 characters',
        ],
        precio_entradaRules: [
          v => !!v || 'Precio de entrada requerido'
        ],
        proveedorRules: [
          v => !!v || 'Proveedor requerido',
          //v => (v && v.length <= 10) || 'Name must be less than 10 characters',
        ],
        fecha_entradaRules: [
          v => !!v || 'Fecha de entrada requerida'
        ],
        /*fecha_salidaRules: [
          v => !!v || 'Fecha de salida requerida'
        ],*/
        valid: true,
        formTitle: "",
        fecha_entrada: false,
        fecha_salida: false,
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
          { text: 'Proveedor', value: 'proveedor'},
          { text: 'Fecha de entrada', value: 'fecha_entrada'},
          { text: 'Precio de entrada', value: 'precio_entrada'},
          { text: 'Fecha de salida', value: 'fecha_salida'},
          { text: 'Precio de salida', value: 'precio_salida'},
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
          codbarras: null,
          tipo: "",
          marca: "",
          modelo: "",
          proveedor: null,
          fecha_entrada: null,
          fecha_salida: null,
          precio_entrada: null,
          precio_salida: null,
          seriales: null
        },
        selectedItem: {
          id: 0,
          codbarras: null,
          tipo: "",
          marca: "",
          modelo: "",
          proveedor: null,
          fecha_entrada: null,
          fecha_salida: null,
          precio_entrada: null,
          precio_salida: null,
          seriales: null
        }
      }
    },
    computed:
    {
      fullTitle: function()
      {
        if(this.selectedItem.codbarras)
        {
          return this.formTitle + ': ' + this.selectedItem.codbarras.modelo
        }
        else
        {
          return this.formTitle
        }
      },
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
          const { sortBy, descending, page, itemsPerPage, sortDesc } = this.options
          axios.post('/datatables/getstock',
          {
            sortDesc: this.options.sortDesc,
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
        console.log(item)
        this.selectedItem = Object.assign({}, {
          id: item.id,
          codbarras: {
            value: item.prods_id,
            text: item.codbarras,
            modelo: item.modelo
          },
          tipo: item.tipo,
          marca: item.marca,
          modelo: item.modelo,
          proveedor: {
            value: item.provs_id,
            text: item.proveedor
          },
          fecha_entrada: item.fecha_entrada,
          fecha_salida: item.fecha_salida,
          precio_entrada: item.precio_entrada,
          precio_salida: item.precio_salida,
          serial: item.serial
        })
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
          axios.post('/admin/stock/editar',
                this.selectedItem).then(response => {
                  
                });
        }
        else if(this.formTitle == "Eliminar producto")
        {
          axios.post('/admin/stock/eliminar',
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