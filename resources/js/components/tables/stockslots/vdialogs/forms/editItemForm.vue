<template>
	<v-form ref="form" v-model="valid">
		{{item}}
		<v-card>
          	<v-card-title>
            	<span class="headline">Editar producto</span>
          	</v-card-title>
			<v-card-text>
				<v-container grid-list-mid>
					<v-layout wrap>
						<v-flex xs18 sm9 md6>
				  			<autocomplete itemsValue="id" v-model="item.codbarras" palceholder="Escribir para buscar" :default="item.codbarras" label="Producto" url="/ajax/codbarras"></autocomplete>
				    	</v-flex>
				    	<v-flex xs18 sm9 md6>
				    		<combobox :default="item.proveedor" :rules="proveedorRules" v-model="item.proveedor" url="/ajax/proveedores" label="Proveedor"></combobox>
				    	</v-flex>
				    	<v-flex xs18 sm9 md6>
								<datepicker :default="item.fecha_entrada" ref="fecha_entrada" label="Fecha de entrada" v-model="item.fecha_entrada"></datepicker>
				    	</v-flex>
						<v-flex xs18 sm9 md6>
				  			<v-text-field v-model="item.precio_entrada" type="numeric" label="Precio de entrada"></v-text-field>  
						</v-flex>
				        <v-flex xs18 sm9 md6>
				        	<datepicker :default="item.fecha_salida" ref="fecha_salida" v-model="item.fecha_salida" label="Fecha de salida"></datepicker>	
				        </v-flex>
						<v-flex xs18 sm9 md6>
				  			<v-text-field v-model="item.precio_salida" type="numeric" label="Precio de salida" required></v-text-field>  
						</v-flex>
						<v-flex xs18 sm9 md6>
							<autocomplete itemsValue="id" label="Cliente" v-model="item.cliente" placeholder="Escribir para buscar" :default="item.cliente" url="/ajax/clientes"></autocomplete>    
						</v-flex>
						<v-flex xs36 sm18 md12 v-if="item.codbarras.serializado == true">
							<combobox :default="item.serial" disabledAll v-model="item.serial" url="/ajax/seriales"></combobox>
                      	</v-flex>
					</v-layout>
				</v-container>
			</v-card-text>
			<v-card-actions>
	            <v-spacer></v-spacer>
	            <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
	            <v-btn :disabled="!valid" color="success" text @click="save">Save</v-btn>
	      	</v-card-actions>
      	</v-card>
    </v-form>
</template>
<script>
	import combobox from './formInputs/combobox'
	import datepicker from './formInputs/datepicker'
	import autocomplete from './formInputs/autocomplete'
	import { mapGetters } from 'vuex'
	export default{
		components:
		{
			autocomplete,
			combobox,
			datepicker
		},
		computed:
		{
			getItem:{
				get()
				{
					return this.$store.getters.getItem
				},
				set(value)
				{
					this.$store.commit('setItem', value)
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
		        ],
		        //fecha_entrada: false,
        		//fecha_salida: false,
		        isLoading: false,
		        //searchAuto: null,
        		//searchAutoClientes: null,
		        item: {
		          	id: 0,
		          	codbarras: null,
		          	proveedor: null,
		          	fecha_entrada: null,
		          	fecha_salida: null,
		          	precio_entrada: null,
		          	precio_salida: null,
          			serial: null,
          			cliente: null
        		},
		        valid: true
	        }
		},
		methods:
		{
			close () {
        		this.$store.commit('setDialog', false)
        		this.$store.commit('setAction', null)
        		this.$store.commit('setItem', null)
        		setTimeout(() => {
          			this.item = Object.assign({}, this.defaultItem)
          			this.editedIndex = -1
          			//this.getDataFromApi();
        		}, 300)
     		},
     		save()
     		{
     			axios.post('/admin/stock/editar',
                this.item).then(response => {
              		
                });
                this.close();
     		}
		},
		props:
		{
			disabledAll: Boolean
		},
		beforeMount()
		{
			this.item = Object.assign({}, {
				          	id: this.getItem.id,
				          	codbarras: {
					            id: this.getItem.productos_id,
					            upc: this.getItem.upc,
					            ean: this.getItem.ean,
					            text: this.getItem.modelo,
					            serializado: this.getItem.serializado
				          	},
			          		//tipo: this.getItem.tipo,
			          		//marca: this.getItem.marca,
			          		//modelo: this.getItem.modelo,
				          	proveedor: {
					            value: this.getItem.proveedores_id,
				            	text: this.getItem.proveedor
				          	},
							cliente: {
					            nombre: this.getItem.nombre,
					            apellido: this.getItem.apellido,
					            id: this.getItem.clienteid,
					            documento: this.getItem.documento,
					            domicilio: this.getItem.domicilio,
					            correo: this.getItem.correo
				          	},
							fecha_entrada: this.getItem.fecha_entrada,
							fecha_salida: this.getItem.fecha_salida,
							precio_entrada: this.getItem.precio_entrada,
							precio_salida: this.getItem.precio_salida,
							serial: this.getItem.serial
			        	})
		}		
	}
</script>