<template>
	<v-dialog persistent v-model="getDialog" max-width="600px">
		<newItemForm></newItemForm>
                      		
                      
                      <!--<v-combobox
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
                    		
                    
                    
                      	<!--</v-layout>
                	</v-container>-->

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
	import editItemForm from './forms/editItemForm'
	import newItemForm from './forms/newItemForm'
	export default{
		components:
		{
			editItemForm,
			newItemForm
		},
		computed:
		{
			getDialog: {
				get()
				{
					return this.$store.getters.getDialog
				},
				set(value)
				{
					this.$store.commit('setDialog', value)
				}
			}
		},
		data()
		{
			return{
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
		        ]
			}
		},
		methods:
		{
      		/*close () {
      			this.getDialog = false;
        		setTimeout(() => {
	          		this.$store.commit('setRunSearch', true)
        		}, 300)
      		},*/
		}

	}
</script>