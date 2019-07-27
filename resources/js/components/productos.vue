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
                      <v-flex xs12 sm6 md4>
                        <v-container fluid>
                          <v-switch v-model="selectedItem.serializado" color="blue" label="Serializado"></v-switch>
                        </v-container>
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
    <template v-slot:item.serializado="{ item }">
      <v-chip :color="getColor(item.serializado)" dark>{{ getLabel(item.serializado)}}</v-chip>
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
      <v-btn color="primary" @click="initialize">Refresh</v-btn>
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
          { text: 'Serializado', value: 'serializado'},
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
          modelo: "",
          serializado: false
        },
        selectedItem: {
          id: 0,
          codbarras: 0,
          tipo: "",
          marca: "",
          modelo: "",
          serializado: false
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
      getColor(item)
      {
        console.log(item)
        if(item == true)
        {
          return 'green'
        }
        else
        {
          return 'red'
        }
      },
      getLabel(item)
      {
        if(item == true)
        {
          return 'Activo'
        }
        else
        {
          return 'Inactivo'
        }
      },
      getDataFromApi () {
        this.loading = true
          const { sortBy, descending, page, itemsPerPage, sortDesc } = this.options
          axios.post('/datatables/getproductos',
          {
            sortDesc: this.options.sortDesc,
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
      initialize () {
        this.getDataFromApi();
      },
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