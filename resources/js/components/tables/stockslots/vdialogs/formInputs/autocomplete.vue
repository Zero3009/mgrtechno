<template>
	<v-autocomplete
      	v-model="val"
      	:items="items"
      	:loading="isLoading"
      	:search-input.sync="search"
      	color="white"
      	hide-no-data
      	hide-selected
      	item-text="modelo"
      	item-value="id"
      	no-filter
      	:label="label"
      	:placeholder="placeholder"
      	prepend-icon="mdi-database-search"
      	return-object
    >
    <template v-slot:item="data">
		<v-list-item-content>
			<v-list-item-title v-html="data.item.modelo"></v-list-item-title>
			<v-list-item-title v-html="data.item.nombre"></v-list-item-title>
			<v-list-item-subtitle v-if="data.item.ean" v-html="'EAN:' + data.item.ean"></v-list-item-subtitle>
			<v-list-item-subtitle v-if="data.item.upc" v-html="'UPC:' + data.item.upc"></v-list-item-subtitle>
		</v-list-item-content>
	</template>
       	<!--<template v-slot:item="data">
          	<v-list-item-content>
            	<v-list-item-title v-html="data.item.nombre + ' '+ data.item.apellido"></v-list-item-title>
            	<v-list-item-subtitle v-html="'DNI:' + data.item.documento"></v-list-item-subtitle>
            	<v-list-item-subtitle v-html="'Email:' + data.item.email"></v-list-item-subtitle>
          	</v-list-item-content>
      	</template>-->
    </v-autocomplete>
</template>
<script>
	export default{
		data()
		{
			return{
				isLoading: false,
				val: null,
				items:null,
				search: ''
			}
		},
		methods:
		{
			save()
			{
				this.$emit('input', this.val)				
			}
		},
		props:['label','placeholder','url','value'],
		watch:
		{
			val:
			{
				handler()
				{
					this.save()
				},
				deep: true
			},
			search (val) {
	        	this.isLoading = true
	        	axios.post(this.url,
	          	{
	            	search:val
	          	}).then(response => {
	            	this.items = response.data;
	            	this.isLoading = false;
	        	});
      		},
		}
	}
</script>