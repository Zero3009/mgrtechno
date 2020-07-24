<template>
	<v-autocomplete
      	v-model="val"
      	:items="items"
      	:loading="isLoading"
      	:search-input.sync="search"
      	color="white"
      	hide-no-data
      	hide-selected
      	item-text="text"
      	:item-value="itemsValue"
      	no-filter
      	:label="label"
      	:placeholder="placeholder"
      	prepend-icon="mdi-database-search"
      	return-object
    >
    <template v-slot:item="item">
		<v-list-item-content>
			<v-list-item-title v-if="item.item.modelo" v-html="item.item.marca + ' ' +item.item.modelo"></v-list-item-title>
			<v-list-item-title v-if="item.item.text" v-html="item.item.text + ' ' + item.item.apellido"></v-list-item-title>
			<v-list-item-subtitle v-if="item.item.documento" v-html="'DNI:' + item.item.documento"></v-list-item-subtitle>
			<v-list-item-subtitle v-if="item.item.ean" v-html="'EAN:' + item.item.ean"></v-list-item-subtitle>
			<v-list-item-subtitle v-if="item.item.ean" v-html="'EAN:' + item.item.ean"></v-list-item-subtitle>
			<v-list-item-subtitle v-if="item.item.upc" v-html="'UPC:' + item.item.upc"></v-list-item-subtitle>
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
				val: this.default,
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
		props:['label','placeholder','url','value','default','itemsValue'],
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