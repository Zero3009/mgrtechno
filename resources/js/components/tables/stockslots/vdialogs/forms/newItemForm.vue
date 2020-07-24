<template>
	<v-form ref="form" v-model="valid">
		<v-card>
          	<v-card-title>
            	<span class="headline">Nuevo producto</span>
          	</v-card-title>
			<v-card-text>
				<v-container grid-list-mid>
					<v-layout wrap>
						<v-flex xs18 sm9 md6>
				  			<autocomplete v-model="item.codbarras" palceholder="Escribir para buscar" label="Producto" url="/ajax/codbarras"></autocomplete>
				    	</v-flex>
				    	<v-flex xs18 sm9 md6>
				    		<combobox :rules="proveedorRules" v-model="item.proveedor" url="/ajax/proveedores" label="Proveedor"></combobox>
				    	</v-flex>
				    	{{item}}
				    	<v-flex xs18 sm9 md6>
								<datepicker ref="fecha_entrada" label="Fecha de entrada" v-model="item.fecha_entrada"></datepicker>
				    	</v-flex>
						<v-flex xs18 sm9 md6>
				  			<v-text-field v-model="item.precio_entrada" type="numeric" label="Precio de entrada"></v-text-field>  
						</v-flex>
				        <v-flex xs18 sm9 md6>
				        	<datepicker ref="fecha_salida" v-model="item.fecha_salida" label="Fecha de salida"></datepicker>	
				        </v-flex>
						<v-flex xs18 sm9 md6>
				  			<v-text-field v-model="item.precio_salida" type="numeric" label="Precio de salida" required></v-text-field>  
						</v-flex>
						<v-flex xs18 sm9 md6>
							<autocomplete label="Cliente" v-model="item.cliente" placeholder="Escribir para buscar" url="/ajax/clientes"></autocomplete>    
						</v-flex>
						<v-flex xs36 sm18 md12 v-if="serializado == true">
							<combobox v-model="item.seriales" label="Seriales" url="/ajax/seriales" multiple disabledAll>
							</combobox>
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

	export default{
		components:
		{
			autocomplete,
			combobox,
			datepicker
		},
		computed:
		{
			serializado: function()
			{
				if(this.item.codbarras)
				{
					if(this.item.codbarras.serializado == true)
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
		        valid: true
	        }
		},
		methods:
		{
			close () {
        		this.$store.commit('setDialog', false)
        		this.$store.commit('setAction', null)
        		setTimeout(() => {
          			this.selectedItem = Object.assign({}, this.defaultItem)
          			this.editedIndex = -1
          			//this.getDataFromApi();
        		}, 300)
     		},
     		save()
     		{
     			axios.post('/admin/stock/nuevo',
                this.item).then(response => {
                  
                });
                this.close();
     		}
		},
		props: {
			multiple: Boolean,
			disabledAll: Boolean
		}
	}
</script>