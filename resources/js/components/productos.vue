<template>
	<div>{{pagination}}
		<v-data-table
    		:headers="headers"
		    :items="items"
		    :items-per-page="pagination"
		    :options.sync="options"
		    :loading="loading"
		    class="elevation-1"
		    :server-items-length="totalrows"
		    @update:pagination="updatePagination"
		    :footer-props="{
		   		itemsPerPageOptions: paginations,
		      	showFirstLastPage: true,
		      	firstIcon: 'mdi-arrow-collapse-left',
		      	lastIcon: 'mdi-arrow-collapse-right',
		      	prevIcon: 'mdi-minus',
		      	nextIcon: 'mdi-plus'
		    }"
  		>
    <template v-slot:top>
      <v-toolbar flat>
        <v-toolbar-title>My CRUD</v-toolbar-title>
        <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="search"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
    </v-card-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ on }">
            <v-btn color="primary" dark class="mb-2" v-on="on">New Item</v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12 sm6 md4>
                    <v-text-field v-model="editedItem.name" label="Dessert name"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm6 md4>
                    <v-text-field v-model="editedItem.calories" label="Calories"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm6 md4>
                    <v-text-field v-model="editedItem.fat" label="Fat (g)"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm6 md4>
                    <v-text-field v-model="editedItem.carbs" label="Carbs (g)"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm6 md4>
                    <v-text-field v-model="editedItem.protein" label="Protein (g)"></v-text-field>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="save">Save</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.action="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editItem(item)"
      >
        edit
      </v-icon>
      <v-icon
        small
        @click="deleteItem(item)"
      >
        delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize">Reset</v-btn>
    </template>
  </v-data-table>
		<!--<div class="card-header" style="background: #222d32   ; color: #FFFFFF;  opacity: 0.9;">
            <div class="row">
                <div class="col-md-4" style="float: left;">
                    <h3 class="panel-title" style="margin-top: 10px;">Gestionar productos</h3>
                </div>

                <div class="col-md-8" style="float: right;">
                    <a class="btn btn-success" @click="ShowModal" style="float: right;">
                    <i class="fa fa-plus"></i> Nuevo</a>
                </div>
            </div>
        </div>
        <div class="card-body" style="background: #D7D7D7">
			<productostable ref="vuetab"></productostable>
		</div>
        <div class="panel-footer">
        </div>
        <b-modal id="modal1" ref="modal" title="Nuevo registro">
        	<form @submit.prevent="newItem" accept-charset="UTF-8" class="form-horizontal">
	        	<div class="controls">
	                <div class="form-row">
	                    <div class="col-md-6">
	                        <div class="form-group">
	                            <label for="tipo-select">Tipo:</label>
	                        	<v-select :options="tipos" v-model="data.tipo" id="tipo-select" placeholder="Tipo de producto" taggable required></v-select>
	                        </div>
	                    </div>
	                    <div class="col-md-6">
	                        <div class="form-group">
	                            <label for="marca-select">Marca:</label> 
	            				<v-select :options="marcas" id="marca-select" v-model="data.marca" placeholder="Marca" taggable required></v-select>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="form-group col-md-6">
	                        <div class="form-group">
	                            <label for="modelo">Modelo:</label>
	                            <input class="form-control" id="modelo" style="width: 100%" name="modelo" v-model="data.modelo" type="text" required>
	                        </div>
	                    </div>
	                    <div class="form-group col-md-6">
	                        <div class="form-group">
	                            <label for="codbarras">Código de barras:</label>
	            				<input class="form-control" id="codbarras" stype="text" v-model="data.codbarras" name="codbarras" required>
	                        </div>
	                   </div>
	                </div>
	            </div>
	            <div class="d-flex justify-content-end" style="margin-top: 10px">
		            <button class="btn btn-secondary" type="button" @click="hideModal" style="margin-right: 5px">Cancelar</button>
		            <button class="btn btn-success" type="submit">Aceptar</button>
		         </div>
            </form>
            <div slot="modal-footer"></div>
        </b-modal>
        {{tipos}}-->
	</div>
</template>
<script>
  export default {
    data: () => ({
    	paginations:[1,5,15,25],
    	items: [],
    	loading: true,
      	dialog: false,
      	options: {},
      	totalrows: 1,
      	page: 1,
      	pagination: 1,
      	search: "",
    	pageCount: 0,
      	headers: [
	        {
	          text: 'Código de barras',
	          align: 'left',
	          sortable: true,
	          value: 'codbarras',
	        },
	        { text: 'Tipo de producto', value: 'tipo' },
	        { text: 'Modelo', value: 'modelo' },
	        { text: 'Marca', value: 'marca' },
	        { text: 'Actions', value: 'action', sortable: false },
      	],
      editedIndex: -1,
      editedItem: {
        name: '',
        calories: 0,
        fat: 0,
        carbs: 0,
        protein: 0,
      },
      defaultItem: {
        name: '',
        calories: 0,
        fat: 0,
        carbs: 0,
        protein: 0,
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
      }
    },

    watch: {
      	dialog (val) {
        	val || this.close()
      	},
      	pagination: function() {
      		this.getPage()
      	},
      	search: function () {
      		this.getDataFromApi()
      	},
		options: {
	        handler () {
	          this.getDataFromApi()
	            .then(data => {
	              	this.desserts = data.items
	              	this.totalDesserts = data.total
	            })
	        },
	        deep: true,
      	},
    },

    created () {
      this.initialize()
    },

    methods: {
    	/*getDataFromApi: function()
    	{
    		this.loading = true;
    		let promise = axios.post('/datatables/getproductos',{
		        	search: this.search,
		        	pagination: this.pagination
		        })

		        return promise.then((data) => {
		        	this.loading = false;
		          const items = data.data.data
		          this.items = items
		        }).catch(error => {
		          return []
		        })
    	},*/
    	getDataFromApi () {
        this.loading = true
        return new Promise((resolve, reject) => {
        	console.log(resolve)
        	console.log(reject)
          const { sortBy, descending, page, itemsPerPage } = this.options

          let items = axios.post('/datatables/getproductos',{
		        	search: this.search,
		        	pagination: this.pagination
		        })
          const total = items.length

          /*if (this.options.sortBy) {
            items = items.sort((a, b) => {
              const sortA = a[sortBy]
              const sortB = b[sortBy]

              if (descending) {
                if (sortA < sortB) return 1
                if (sortA > sortB) return -1
                return 0
              } else {
                if (sortA < sortB) return -1
                if (sortA > sortB) return 1
                return 0
              }
            })
          }

          if (itemsPerPage > 0) {
            items = items.slice((page - 1) * itemsPerPage, page * itemsPerPage)
          }

          setTimeout(() => {
            this.loading = false
            resolve({
              items,
              total,
            })
          }, 1000)*/
        })
      },
    	updatePagination(pagination)
    	{
    		console.log(pagination)
    	},
    	getSearch()
    	{
    		console.log(this.search)
    	},
    	getPage()
    	{
    		console.log(this.pagination)
    	},
      initialize () {
        this.desserts = [
          {
            name: 'Frozen Yogurt',
            calories: 159,
            fat: 6.0,
            carbs: 24,
            protein: 4.0,
          },
          {
            name: 'Ice cream sandwich',
            calories: 237,
            fat: 9.0,
            carbs: 37,
            protein: 4.3,
          },
          {
            name: 'Eclair',
            calories: 262,
            fat: 16.0,
            carbs: 23,
            protein: 6.0,
          },
          {
            name: 'Cupcake',
            calories: 305,
            fat: 3.7,
            carbs: 67,
            protein: 4.3,
          },
          {
            name: 'Gingerbread',
            calories: 356,
            fat: 16.0,
            carbs: 49,
            protein: 3.9,
          },
          {
            name: 'Jelly bean',
            calories: 375,
            fat: 0.0,
            carbs: 94,
            protein: 0.0,
          },
          {
            name: 'Lollipop',
            calories: 392,
            fat: 0.2,
            carbs: 98,
            protein: 0,
          },
          {
            name: 'Honeycomb',
            calories: 408,
            fat: 3.2,
            carbs: 87,
            protein: 6.5,
          },
          {
            name: 'Donut',
            calories: 452,
            fat: 25.0,
            carbs: 51,
            protein: 4.9,
          },
          {
            name: 'KitKat',
            calories: 518,
            fat: 26.0,
            carbs: 65,
            protein: 7,
          },
        ]
      },

      editItem (item) {
        this.editedIndex = this.desserts.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.desserts.indexOf(item)
        confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
      },

      close () {
        this.dialog = false
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        }, 300)
      },

      save () {
        if (this.editedIndex > -1) {
          Object.assign(this.desserts[this.editedIndex], this.editedItem)
        } else {
          this.desserts.push(this.editedItem)
        }
        this.close()
      },
      	myProvider (ctx) {
		        let promise = axios.post('/datatables/getproductos',{
		        	ctx: ctx
		        })

		        return promise.then((data) => {
		          const items = data.data.data
		          return(items)
		        }).catch(error => {
		          return []
		        })
	    },
    },
  }
</script>
<!--<script>
	import productostable from './tables/productostable';
	export default {
		data: function()
		{
			var data = 
			{
				data: 
				{
					 tipo: null,
					 marca: null,
					 modelo: "",
					 codbarras: ""
				},
				tipo: null,
				tipos: [],
				marca: null,
				marcas: [],
                modelo: "",
                codbarras: "",
                csrf: $('meta[name=csrf-token]').attr('content')
			}
			return data;
		},
		components: 
		{
			productostable
		},
		computed:
		{
			ajaxNuevo: function()
			{
				if(data.tipo != null && data.marca != null && data.modelo != "" && data.codbarras != "")
				{
					return this.data
				}
				return  'fail'
			}
		},
		methods:
		{
			getMarcas: function()
			{
				return this.marcas;
			},
			getTipos: function()
			{
				return this.tipos;
			},
			ShowModal: function()
			{
				this.cargarSelects();
				this.$refs.modal.show();
			},
			hideModal: function()
			{
				this.$refs.modal.hide();
			},
			cargarSelects: function()
			{
				this.tiposdeproductos();
				this.getmarcas();
			},
			tiposdeproductos: function()
            {
                axios.get('/ajax/tiposprods')
                    .then(response => {
                        this.tipos = response.data;
                });
            },
            getmarcas: function()
            {
            	axios.get('/ajax/marcas')
            		.then(response => {
            			this.marcas = response.data;		
        		});
            },
            newItem: function()
            {
            	//this.$refs.vuetab.$refs.vuetable.refresh()
            	//this.$refs.vuetab.$chilren.$refs.vuetable.refresh()
            	axios.post('/admin/productos/nuevo',
            		this.data).then(response => {
            			this.$refs.modal.hide();
            			this.$refs.vuetab.$refs.table.refresh();
            		});
            }
		},
		beforeMount()
		{
			this.cargarSelects()
		}
	}
</script>-->