<template>
    <v-card>
      <!--<v-card-title>
        Stock
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>-->
      <v-data-table
        :search="search"
        :headers="headers"
        :items="desserts"
        :options.sync="options"
        :server-items-length="totalDesserts"
        :loading="loading"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar flat>
          <v-toolbar-title>Productos</v-toolbar-title>
          <v-divider
            class="mx-4"
            inset
            vertical
          ></v-divider>
          <v-spacer></v-spacer>
          <v-text-field
          v-model="search"
          append-icon="search"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="500px">
              <template v-slot:activator="{ on }">
                <v-btn color="primary" dark class="mb-2" v-on:click="newItem()">New Item</v-btn>
              </template>
              <v-card  v-if="formCalc == false">
                <v-card-title>
                  <span class="headline">{{ formTitle }}</span>
                </v-card-title>
                <v-card-text>
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs12 sm6 md4>
                        <v-text-field v-model="selectedItem.codbarras" label="Código de barras"></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm6 md4>
                        <!--<v-text-field v-model="selectedItem.tipo" label="Tipo de producto"></v-text-field>-->
                        <v-combobox
                          v-model="selectedItem.tipo"
                          :items="comboboxes.fields.tipos"
                          :search-input.sync="comboboxes.searching.tipo"
                          hide-selected
                          hint="Seleccione la marca, si no existe escribala"
                          label="Tipo de producto"
                          small-chips
                          persistent-hint
                        >
                          <template v-slot:no-data>
                            <v-list-item>
                              <v-list-item-content>
                                <v-list-item-title>
                                  No se encontraron resultados para "<strong>{{ comboboxes.searching.tipo }}</strong>". Presiona <kbd>enter</kbd> para crearlo
                                </v-list-item-title>
                              </v-list-item-content>
                            </v-list-item>
                          </template>
                        </v-combobox>
                      </v-flex>
                      <v-flex xs12 sm6 md4>
                        <v-combobox
                          v-model="selectedItem.marca"
                          :items="comboboxes.fields.marcas"
                          :search-input.sync="comboboxes.searching.marca"
                          hide-selected
                          hint="Seleccione el tipo de producto, si no existe escribalo"
                          label="Marca"
                          persistent-hint
                        >
                          <template v-slot:no-data>
                            <v-list-item>
                              <v-list-item-content>
                                <v-list-item-title>
                                  No se encontraron resultados para "<strong>{{ comboboxes.searching.marca }}</strong>". Presiona <kbd>enter</kbd> para crearlo
                                </v-list-item-title>
                              </v-list-item-content>
                            </v-list-item>
                          </template>
                        </v-combobox>
                        <!--<v-text-field v-model="selectedItem.marca" label="Marca"></v-text-field>-->
                      </v-flex>
                      <v-flex xs12 sm6 md4>
                        <v-text-field v-model="selectedItem.modelo" label="Modelo"></v-text-field>
                      </v-flex>
                      <!--<v-flex xs12 sm6 md4>
                        <v-text-field v-model="editedItem.protein" label="Protein (g)"></v-text-field>
                      </v-flex>-->
                    </v-layout>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                  <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                </v-card-actions>
              </v-card>
              <v-card v-else>
                <v-card-title>
                  <span class="headline">¿Seguro que quiere eliminar el producto?</span>
                </v-card-title>
                <v-card-text>

                  <v-simple-table
                    :dense="dense"
                    :fixed-header="fixedHeader"
                    :height="height"
                  >
                    <tbody>
                      <tr>
                        <td>Marca:</td>
                        <td>{{ selectedItem.marca }}</td>
                      </tr>
                      <tr>
                        <td>Modelo:</td>
                        <td>{{ selectedItem.modelo }}</td>
                      </tr>
                      <tr>
                        <td>Tipo de producto:</td>
                        <td>{{ selectedItem.tipo }}</td>
                      </tr>
                      <tr>
                        <td>Código de barras:</td>
                        <td>{{ selectedItem.codbarras }}</td>
                      </tr>
                    </tbody>
                  </v-simple-table>
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
    </v-card>
</template>
<script>
  export default {
    data () {
      return {
        formTitle: "",
        comboboxes:
        {
          fields:{
            tipos: [],
            marcas: []  
          },
          searching:{
            tipo: "",
            marca: ""
          }
        },
        dialog: false,
        search:'',
        totalDesserts: 0,
        desserts: [],
        loading: true,
        options: {},
        headers: [
          { text: 'ID', value:'id'},
          { text: 'Código de barras', value: 'codbarras' },
          { text: 'Tipo de producto', value: 'tipo' },
          { text: 'Modelo', value: 'modelo' },
          { text: 'Marca', value: 'marca' },
          { text: 'Actions', value: 'action', sortable: false },
        ],
        //EXPERIMENTAL
        editedIndex: -1,
        editedItem: {
          name: '',
          calories: 0,
          fat: 0,
          carbs: 0,
          protein: 0,
        },
        defaultItem: {
          id: 0,
          codbarras: 0,
          tipo: "",
          marca: "",
          modelo: ""
        },
        selectedItem: {
          id: 0,
          codbarras: 0,
          tipo: "",
          marca: "",
          modelo: ""
        }
      }
    },
    computed:
    {
      formCalc: function()
      {
        if(this.formTitle == 'Eliminar producto')
        {
          return true
        }
        else
        {
          return false
        }

      }
    },
    watch: {
      search:{
        handler () {
          this.getDataFromApi()
        },
        deep: true,
      },
      options: {
        handler () {
          this.getDataFromApi()
        },
        deep: true,
      },
      dialog (val) {
        val || this.close()
      },
    },
    mounted () {
      this.getDataFromApi()
      this.cargarSelects()
    },
    methods: {
      getDataFromApi () {
        this.loading = true
          const { sortBy, descending, page, itemsPerPage } = this.options
          axios.post('/datatables/getproductos',
          {
            search: this.search,
            sortBy: this.options.sortBy,
            descending: this.options.descending,
            page: this.options.page,
            itemsPerPage: this.options.itemsPerPage
          }).then(response => {
            this.desserts = response.data.data
            this.totalDesserts = response.data.total
            this.loading = false;
          });
      },
      /*initialize () {
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
      },*/
      newItem ()
      {
        this.formTitle = "Nuevo producto"
        this.selectedItem = Object.assign({}, this.defaultItem)
        this.dialog = true  
      },
      editItem (item) {
        this.formTitle = "Editar producto"
        this.selectedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        this.formTitle = "Eliminar producto"
        this.selectedItem = Object.assign({}, item)
        this.dialog = true
      },

      close () {
        this.dialog = false
        setTimeout(() => {
          this.selectedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
          this.getDataFromApi();
        }, 300)
      },

      save () {
        if(this.formTitle == "Nuevo producto")
        {
          axios.post('/admin/productos/nuevo',
                this.selectedItem).then(response => {
                  
                });
        }
        else if(this.formTitle == "Editar producto")
        {
          axios.post('/admin/productos/editar',
                this.selectedItem).then(response => {
                  
                });
        }
        else if(this.formTitle == "Eliminar producto")
        {
          axios.post('/admin/productos/eliminar',
                {id:this.selectedItem.id}).then(response => {
                    
                });   
        }
        this.close()
      },
      //CARGAR COMBOBOX
      cargarSelects: function()
      {
        this.getTiposProductos();
        this.getMarcas();
      },
      getTiposProductos: function()
      {
          axios.get('/ajax/tiposprods')
            .then(response => {
              this.comboboxes.fields.tipos = response.data;
          });
      },
      getMarcas: function()
      {
        axios.get('/ajax/marcas')
          .then(response => {
            this.comboboxes.fields.marcas = response.data;    
        });
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