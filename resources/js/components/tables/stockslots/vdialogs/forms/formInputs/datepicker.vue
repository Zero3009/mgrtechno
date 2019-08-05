<template>
	<v-menu
        ref="fecha"
        v-model="fecha"
        :close-on-content-click="false"
        :return-value.sync="fecha_value"
        transition="scale-transition"
        offset-y
        full-width
        min-width="290px"
  	>
        <template v-slot:activator="{ on }">
          	<v-text-field
	            v-model="fecha_value"
	            :label="label"
	            prepend-icon="event"
	            readonly
	            v-on="on"
            	:rules="fecha_entradaRules"
          	></v-text-field>
        </template>
        <v-date-picker locale="es-419" v-model="fecha_value" no-title scrollable>
          	<v-spacer></v-spacer>
          	<v-btn text color="primary" @click="fecha = false">Cancel</v-btn>
          	<v-btn text color="primary" @click="save(fecha_value)">OK</v-btn>
        </v-date-picker>
  	</v-menu>
</template>
<script>
	export default{
		data()
		{
			return{
				fecha: false,
				fecha_value: null,
				fecha_entradaRules: [
		          	v => !!v || 'Fecha de entrada requerida'
		        ],
			}
		},
		props:['label','value'],
		methods:{
			save(value)
			{
				this.$refs.fecha.save(this.fecha_value)
				this.$emit('input', this.fecha_value)
			}
		}
	}
</script>