<template>
	<v-dialog persistent v-model="getDialog" max-width="500px">

	        <!--<template v-slot:activator="{ on }">
	          	<v-btn color="primary" dark class="mb-2" v-on:click="newItem()">New Item</v-btn>
	        </template>-->
	        <v-card>
	          	<v-card-title>
	            	<span class="headline">Nuevo producto</span>
	          	</v-card-title>
	            <v-form
	              	ref="form"
	              	v-model="valid"
	            >
	                <v-card-text>
	                  	<v-container grid-list-md>
	                    	<v-layout wrap>
	                      		<v-flex xs18 sm9 md6>
	                      			<autocomplete v-model="selectedItem.codbarras" palceholder="Escribir para buscar" label="Producto" url="/ajax/codbarras"></autocomplete>
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
	                        {{selectedItem}}
	                        <v-flex xs18 sm9 md6>
	                   			<datepicker ref="fecha_entrada" label="Fecha de entrada" v-model="selectedItem.fecha_entrada"></datepicker>
	                        </v-flex>
	                    <v-flex xs18 sm9 md6>
	                      <v-text-field v-model="selectedItem.precio_entrada" :rules="precio_entradaRules" type="numeric" label="Precio de entrada" required></v-text-field>  
	                    </v-flex>
	                    <v-flex xs18 sm9 md6>
	                    	<datepicker ref="fecha_salida" label="Fecha de salida"></datepicker>	
	                    </v-flex>
	                    <v-flex xs18 sm9 md6>
	                      <v-text-field v-model="selectedItem.precio_salida" type="numeric" label="Precio de salida" required></v-text-field>  
	                    </v-flex>
	                    <v-flex xs18 sm9 md6>
	                    	<autocomplete label="Cliente" v-model="selectedItem.cliente" placeholder="Escribir para buscar" url="/ajax/clientes"></autocomplete>    
                        </v-flex>
	                    <v-flex xs36 sm18 md12 v-if="serializado == true">
	                      <!--<v-combobox
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
	                      </v-combobox>-->
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
	        <!--<v-card v-else>
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
	        </v-card>-->
      	</v-dialog>
</template>
<script>
	import datepicker from './formInputs/datepicker'
	import autocomplete from './formInputs/autocomplete'
	export default{
		components:
		{
			datepicker,
			autocomplete
		},
		computed:
		{
			/*...mapGetters([
				'getDialog'
			])*/
			getDialog: {
				get()
				{	console.log(this.$store.getters.getDialog)
					return this.$store.getters.getDialog
				},
				set(value)
				{
					this.$store.commit('setDialog', value)
				}
			},
			serializado: function()
	      	{
		        if(this.selectedItem.codbarras)
		        {
		          	if(this.selectedItem.codbarras.serializado == true)
		          	{
		            	return true
		          	}
		        }
		        return false
	      	}

		},
		data()
		{
			return{
				comboboxes:
				{
          			fields:{
			            codbarras: [],
			            tipos: [],
			            marcas: [],
			            proveedores: [],
			            seriales: [],
			            clientes: []
          			},
          			searching:{
			            codbarras: null,
			            tipo: null,
			            marca: null,
			            proveedor: null,
			            seriales: null
          			},
          			loading:
          			{
			            proveedores: false,
			            clientes: false
          			}
        		},
				serialesRules: [
          			v => !!v || 'Debe ingresar al menos un serial'
		        ],
		        codbarrasRules: [
		          	v => !!v || 'Producto requerido',
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
		        fecha_entrada: false,
        		fecha_salida: false,
		        isLoading: false,
		        searchAuto: null,
        		searchAutoClientes: null,
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
          			seriales: null,
          			cliente: null
        		},
		        valid: true,
			}
		},
		methods:
		{
			save () {
		        /*if(this.formTitle == "Nuevo producto")
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
		                    
                });*/   
	        
        		this.close()
      		},
      		close () {
        		this.search = false
        		setTimeout(() => {
	          		this.selectedItem = Object.assign({}, this.defaultItem)
	          		this.editedIndex = -1
	          		this.getDataFromApi();
        		}, 300)
      		},
		},
		watch:
		{

		}
	}
</script>