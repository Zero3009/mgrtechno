<template>
	<v-data-table
        :single-expand="singleExpand"
        :expanded.sync="expanded"
        :search="search"
        :headers="headers"
        :items="desserts"
        :options.sync="options"
        :server-items-length="totalDesserts"
        :loading="loading"
        class="elevation-1"
        show-expand
        show-select
        v-model="multiSalida"
      >
        <template v-slot:top>
          <slottop>
          </slottop>
          <!--<v-toolbar flat>
          <v-spacer></v-spacer>
          <v-btn color="success" dark class="mb-2" v-on:click="multiInit()">Salida multiple</v-btn>
          <v-dialog persistent v-model="dialog" max-width="600px">
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
                    <v-card-text>
                      <v-container grid-list-md>
                        <v-layout wrap>
                          <v-flex xs18 sm9 md6>
                            <v-autocomplete
                              v-model="selectedItem.codbarras"
                              :items="comboboxes.fields.codbarras"
                              :loading="isLoading"
                              :search-input.sync="searchAuto"
                              color="white"
                              hide-no-data
                              hide-selected
                              item-text="modelo"
                              item-value="id"
                              no-filter
                              label="Producto:"
                              placeholder="Escribir para buscar"
                              prepend-icon="mdi-database-search"
                              return-object
                              :rules="codbarrasRules"
                            >
                               <template v-slot:item="data">
                                  <v-list-item-content>
                                    <v-list-item-title v-html="data.item.modelo"></v-list-item-title>
                                    <v-list-item-subtitle v-if="data.item.ean" v-html="'EAN:' + data.item.ean"></v-list-item-subtitle>
                                    <v-list-item-subtitle v-if="data.item.upc" v-html="'UPC:' + data.item.upc"></v-list-item-subtitle>
                                  </v-list-item-content>
                              </template>
                            </v-autocomplete>
                            </v-flex>
                            <v-flex xs18 sm9 md6>
                              <v-combobox
                                v-model="selectedItem.proveedor"
                                :items="comboboxes.fields.proveedores"
                                :search-input.sync="comboboxes.searching.proveedor"
                                hide-selected
                                hint="Seleccione la marca, si no existe escribala"
                                label="Proveedor"
                                no-filter
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
                        <v-flex xs18 sm9 md6>
                            <v-autocomplete
                              v-model="selectedItem.cliente"
                              :items="comboboxes.fields.clientes"
                              :loading="isLoading"
                              :search-input.sync="searchAutoClientes"
                              color="white"
                              hide-no-data
                              hide-selected
                              item-text="nombre"
                              item-value="id"
                              no-filter
                              label="Cliente:"
                              placeholder="Escribir para buscar"
                              prepend-icon="mdi-database-search"
                              return-object
                            >
                               <template v-slot:item="data">
                                  <v-list-item-content>
                                    <v-list-item-title v-html="data.item.nombre + ' '+ data.item.apellido"></v-list-item-title>
                                    <v-list-item-subtitle v-html="'DNI:' + data.item.documento"></v-list-item-subtitle>
                                    <v-list-item-subtitle v-html="'Email:' + data.item.email"></v-list-item-subtitle>
                                  </v-list-item-content>
                              </template>
                            </v-autocomplete>
                            </v-flex>
                        <v-flex xs36 sm18 md12 v-if="serializado == true">
                          <v-combobox
                            v-if="formTitle == 'Nuevo producto'"
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
                          <v-combobox
                            v-else-if="formTitle == 'Editar producto'"
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
                  dense
                  fixed-header
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
                      <td>Proveedor:</td>
                      <td>{{ selectedItem.proveedor}}</td>
                    </tr>
                    <tr>
                      <td>Fecha de entrada:</td>
                      <td>{{ selectedItem.fecha_entrada }}</td>
                    </tr>
                    <tr>
                      <td>Precio de entrada:</td>
                      <td>{{ selectedItem.precio_entrada }}</td>
                    </tr>
                    <tr>
                      <td>Fecha de salida:</td>
                      <td>{{ selectedItem.fecha_salida }}</td>
                    </tr>
                    <tr>
                      <td>Precio de salida:</td>
                      <td>{{ selectedItem.precio_salida }}</td>
                    </tr>
                  </tbody>
                </v-simple-table>
              </v-card-text>
              <template>
                <v-spacer></v-spacer>
              </template>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                <v-btn color="blue darken-1" text @click="save">Save</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>-->
      </template>
      <template v-slot:expanded-item="{ item }">
        <td :colspan="headers.length">
          <v-simple-table
                  fixed-header
                  allign="center"
                >
                  <tbody>
                    <tr>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Documento</th>
                      <th>Email</th>
                      <th>Domicilio</th>
                    </tr>
                    <tr>
                      <td>{{item.nombre}}</td>
                      <td>{{item.apellido}}</td>
                      <td>{{item.documento}}</td>
                      <td>{{item.email}}</td>
                      <td>{{item.domicilio}}</td>
                    </tr>
                  </tbody>
                </v-simple-table>
        </td>
      </template>
    <template v-slot:item.action="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="outItem(item)"
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
    </template>
      </v-data-table>
</template>
<script>
	import { mapGetters } from 'vuex';
  	import slottop from './stockslots/slottop.vue';
  	export default {
  		components: {
  			slottop
  			
  		},
  		computed:
  		{
  			...mapGetters([
  				'getSearch',
  				'getDisponible',
          'getRunSearch'
			 ]),
  			edititem: {
  				get()
  				{
  					return this.$store.getters.getSelectedItem
  				},
  				set(value)
  				{
  					this.$store.commit('setSelectedItem', value)
  				}
  			}
  		},
  		data()
  		{
  			return {
  				disponible: null,
  				loading: true,
  				options: {},
  				totalDesserts: 0,
        		desserts: [],
  				search:'',
  				singleExpand: false,
        		expanded: [],
        		multiSalida: [],
        		headers: [
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
  			}
  		},
  		methods:
  		{
  			editItem(item)
  			{
          this.$store.commit('setDialog',true)
  				this.$store.commit('setItem', item);
          this.$store.commit('setAction', 'editar');
  			},
  			close () {
        		this.dialog = false
        		setTimeout(() => {
	          		this.selectedItem = Object.assign({}, this.defaultItem)
	          		this.editedIndex = -1
	          		this.getDataFromApi();
        		}, 300)
    		},
    		getDataFromApi () {
      		this.loading = true
          	const { sortBy, descending, page, itemsPerPage, sortDesc } = this.options
          	axios.post('/datatables/getstock',
        		{
	            datafilter: this.disponible,
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
              this.$store.commit('setRunSearch',false)
        		});
    		},
  		},
  		mounted()
  		{
  			this.getDataFromApi();
  		},
  		watch:
  		{
  			
  			getSearch: function(newValue, oldValue) {
                //handler () {
                	this.search = newValue;
		          	this.getDataFromApi()
		        
        },
        getDisponible: function(newValue, oldValue) {
            //handler () {
            this.disponible = newValue;
          	this.getDataFromApi()
        
        },
        getRunSearch: function(newValue, oldValue) {
            this.getDataFromApi()
        },
        options: {
          handler () {
            this.getDataFromApi()
            },
            deep: true,
          },

  		}
  	}
</script>