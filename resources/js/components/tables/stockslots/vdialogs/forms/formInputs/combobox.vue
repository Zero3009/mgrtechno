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
		:multiple="multiple"
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
				val: this.default,
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
        			if(this.disabledAll == true)
        			{
        				var filter = this.default
        				var a = [];
		              	response.data.forEach(function(item){
		              		if(item.text != filter)
		              		{
	                            a.push({
	                              	value: item.value,
	                              	disabled: true,
	                              	text: item.text
	                            });
              				}
              				else if(item.text == filter)
              				{
              					console.log(item.value)
              					a.push({
	                              	value: item.value,
	                              	disabled: false,
	                              	text: item.text
	                            });	
              				}
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
		props:['url','rules','label','disabledAll', 'multiple','default'],
		props:
		{
			url:
			{
				type: String
			},
			rules:
			{
				type: Array
			},
			label:
			{
				type: String
			},
			disabledAll:
			{
				type: Boolean
			},
			multiple:
			{
				type: Boolean
			},
			default:
			{

			}
		},
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