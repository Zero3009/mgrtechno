<template>
	<v-combobox
		v-model="val"
		:items="values"
		:search-input.sync="search"
		hide-selected
		hint="Seleccione la marca, si no existe escribala"
		:label="label"
		no-filter
		persistent-hint
		:rules="rules"
	>
		<template v-slot:no-data>
			<v-list-item>
				<v-list-item-content>
					<v-list-item-title>
						No se encontraron resultados para "<strong>{{ search }}</strong>". Presiona <kbd>enter</kbd> para crearlo
					</v-list-item-title>
				</v-list-item-content>
			</v-list-item>
		</template>
	</v-combobox>
</template>
<script>
	export default{
		data()
		{
			return{
				search: null,
				val: null,
				values: []
			}
		},
		methods:
		{
			getData()
      		{
        		axios.post(this.url,
	        		{
	        			search: this.search	
	        		}
    			)
        		.then(response => {
        			if(this.disabledAll == "true")
        			{
        				var a = [];
		              	response.data.forEach(function(item){
                            a.push({
                              value: item.value,
                              disabled: true,
                              text: item.text
                            });
              			}); 
              			this.values = a;
        			}
        			else
        			{
        				this.values = response.data	
        			}
    				
        		});
      		},
      		save()
			{
				this.$emit('input', this.val)				
			}
		},
		props:['url','rules','label','disabledAll'],
		watch:
		{
			search:
			{
				handler()
				{
					this.getData()
				},
				deep:true
			},
			val:
			{
				handler()
				{
					this.save()
				},
				deep: true
			}
		}
	}
</script>