<template>
	<div>
		{{moreParams}}
		<b-container fluid>
			<b-row>
				<b-col md="6" class="my-1">
					<b-form-group label-cols-sm="3" label="Filter" class="mb-0">
						<b-input-group>
							<b-form-input v-model="filter" placeholder="Type to Search"/>
							<b-input-group-append>
								<b-btn :disabled="!filter" @click="filter = ''">Clear</b-btn>
							</b-input-group-append>
						</b-input-group>
					</b-form-group>
				</b-col>
				<b-col md="6" class="my-1">
					<b-form-group>
						<b-input-group>
							<b-form-select v-model="sortBy" :options="sortOptions">
								<option slot="first" :value="null">-- none --</option>
							</b-form-select>
							<b-form-select :disabled="!sortBy" v-model="sortDesc" slot="apped">
								<option :value="false">Asc</option>
								<option :value ="true">Desc</option>
							</b-form-select>
						</b-input-group>
					</b-form-group>
				</b-col>
				<b-col md="6" class="my-1">
					<b-form-group label-cols-sm="3" label="Sort direction" class="mb-0">
						<b-input-group>
							<b-form-select v-model="sortDirection" slot="append">
								<option value="asc">Asc</option>
								<option value="desc">Desc</option>
								<option value="last">Last</option>
							</b-form-select>
						</b-input-group>
					</b-form-group>
				</b-col>
				<b-col md="6" class="my-1">
					<b-form-group label-cols-sm="3" label="Por página:" class="mb-0">
						<b-form-select :options="pageOptions" v-model="perPage"/>
					</b-form-group>
				</b-col>
			</b-row>


			<!-- tabla -->

			<b-table responsive striped hover bordered show-empty stacked="md" :items="myProvider" :fields="fields" :current-page="currentPage" :per-page="perPage" :filter="filter" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :sort-direction="sortDirection" @filtered="onFiltered">
				<template slot="actions" slot-scope="row">
					<b-button v-if="row.item.fechaSalida == null" variant="success" scope="item" v-on:click="salida(row.item)">
						<i class="far fa-check-circle"></i>
					</b-button>
					<b-button variant="info" scope="item" v-on:click="editar(row.item)">
						<i class="fas fa-edit"></i>
					</b-button>
					<b-button variant="danger" scope="item" v-on:click="delet(row.item)"><i class="far fa-trash-alt"></i></b-button>
				</template>
	
				<!--<span slot="html" slot-scope="data" v-html="data.value"></span>-->
			</b-table>
			<b-row>
		      <b-col md="6" class="my-1">
		        <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
		      </b-col>
	    	</b-row>
		</b-container>
	</div>
</template>
<script>
	//import CssConfig from './CssConfig.js'
	

  	
	export default {
		data: function()
		{

			var datas =
			{
				searchText: "",
				//css: CssConfig,
				moreParams: {},
				items: [],
				totalRows: 1,
				filter: null,
				sortBy :null,
				sortDesc: false,
				sortDirection: 'asc',
				perPage: 5,
				pageOptions: [ 5, 10, 15, 25, 50, 100],
				currentPage: 1,
				fields: [
			        { key: 'codbarras', label: 'Código de barras', sortable: true, sortDirection: 'desc' },
			        {key: 'tipo', label:'Tipo de producto', sortable: true, sortDirection:'desc'},
			        { key: 'marca', label: 'Marca', sortable: true, 'class': 'text-center' },
			        { key: 'modelo', label: 'Modelo', sortable: true },
			        { key: 'actions', label: 'Acciones'}
			      ],
			}
			return datas;
		},
		computed:
		{
			sortOptions () {
			      // Create an options list from our fields
			      return this.fields
			        .filter(f => f.sortable)
			        .map(f => { return { text: f.label, value: f.key } })
		    }
		},
		methods:
		{
			onPaginationData (paginationData) {
      			this.$refs.pagination.setPaginationData(paginationData)
		    },
		    myProvider (ctx) {
        		// Here we don't set isBusy prop, so busy state will be
        		// handled by table itself
        		// this.isBusy = true
        		console.log(ctx);
		        let promise = axios.post('/datatables/getproductos',{
		        	ctx: ctx
		        })

		        return promise.then((data) => {
		          const items = data.data.data
		          return(items)
		        }).catch(error => {
		          // Here we could override the busy state, setting isBusy to false
		          // this.isBusy = false
		          // Returning an empty array, allows table to correctly handle
		          // internal busy state in case of error
		          return []
		        })
		    },
/*

		    stock()
			{
				axios.get('/datatables/getstock')
					.then(response => {
						this.rows = response.data.length;
						this.items = response.data;
					});
			},*/
			onFiltered (filteredItems) {
		      // Trigger pagination to update the number of buttons/pages due to filtering
		      this.totalRows = filteredItems.length
		      this.currentPage = 1
		    },
		}/*,
	    mounted() {
		   this.$events.$on('filter-set', eventData => this.onFilterSet(eventData))
		   this.$events.$on('filter-reset', e => this.onFilterReset())
		},*/
	

	}
</script>