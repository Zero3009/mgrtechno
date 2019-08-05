<template>
	<v-form ref="form" v-model="valid">
		<v-flex xs18 sm9 md6>
  			<autocomplete v-model="item.codbarras" palceholder="Escribir para buscar" label="Producto" url="/ajax/codbarras"></autocomplete>
    	</v-flex>
    	<v-flex xs18 sm9 md6>
    		<combobox :rules="proveedorRules" v-model="item.proveedor" url="/ajax/proveedores" label="Proveedor"></combobox>
    	</v-flex>
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
		<v-flex xs36 sm18 md12 v-if="item.serializado == true">
			<combobox v-model="item.seriales" label="Seriales" url="/ajax/seriales" disabledAll="true">
			</combobox>
		</v-flex>
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
			...mapGetters([
				'getItem'
			])
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
		        valid: true
	        }
		},
		watch:
		{
			getItem:
			{
				handler()
				{
					this.item = Object.assign({}, this.getItem)
				},
				deep:true
			}
		}		
	}
</script>