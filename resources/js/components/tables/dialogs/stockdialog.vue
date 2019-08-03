<template>
<v-dialog v-model="dialog" persistent max-width="600px">
            <template v-slot:activator="{ on }">
              <v-btn color="primary" dark class="mb-2" v-on:click="newItem()">New Item</v-btn>
            </template>
            <!--<v-card  v-if="formCalc == false">-->
            <v-card>
              <v-card-title>
               <!--<span class="headline">{{ fullTitle }}</span>-->
                <span class="headline">Nuevo producto</span>
                <!--  <v-progress-linear
                  v-model="valueDeterminate"
                  color="yellow accent-4"
                ></v-progress-linear>-->
              </v-card-title>
                <v-form
                  ref="form"
                  v-model="valid"
                >
                    <v-card-text>
                      <v-container grid-list-md>
                        <v-layout wrap>
                          <v-flex xs18 sm9 md6>
                            <v-autocomplete
                              v-model="selectedItem.codbarras"
                              :items="comboboxes.fields.codbarras"
                              :loading="isLoading"
                              :search-input.sync="searchAuto"
                              color="white"
                              hide-no-data
                              hide-selected
                              item-text="modelo"
                              item-value="id"
                              no-filter
                              label="Producto:"
                              placeholder="Escribir para buscar"
                              prepend-icon="mdi-database-search"
                              return-object
                              :rules="codbarrasRules"
                            >
                               <template v-slot:item="data">
                                  <v-list-item-content>
                                    <v-list-item-title v-html="data.item.modelo"></v-list-item-title>
                                    <v-list-item-subtitle v-if="data.item.ean" v-html="'EAN:' + data.item.ean"></v-list-item-subtitle>
                                    <v-list-item-subtitle v-if="data.item.upc" v-html="'UPC:' + data.item.upc"></v-list-item-subtitle>
                                  </v-list-item-content>
                              </template>
                            </v-autocomplete>
                            </v-flex>
                            <v-flex xs18 sm9 md6>
                              <v-combobox
                                v-model="selectedItem.proveedor"
                                :items="comboboxes.fields.proveedores"
                                :search-input.sync="comboboxes.searching.proveedor"
                                hide-selected
                                hint="Seleccione la marca, si no existe escribala"
                                label="Proveedor"
                                no-filter
                                persistent-hint
                                :rules="proveedorRules"
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
                            <v-flex xs18 sm9 md6>
                          <v-menu
                            ref="fecha_entrada"
                            v-model="fecha_entrada"
                            :close-on-content-click="false"
                            :return-value.sync="selectedItem.fecha_entrada"
                            transition="scale-transition"
                            offset-y
                            full-width
                            min-width="290px"
                          >
                            <template v-slot:activator="{ on }">
                              <v-text-field
                                v-model="selectedItem.fecha_entrada"
                                label="Fecha de entrada"
                                prepend-icon="event"
                                readonly
                                v-on="on"
                                :rules="fecha_entradaRules"
                              ></v-text-field>
                            </template>
                            <v-date-picker locale="es-419" v-model="selectedItem.fecha_entrada" no-title scrollable>
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="fecha_entrada = false">Cancel</v-btn>
                              <v-btn text color="primary" @click="$refs.fecha_entrada.save(selectedItem.fecha_entrada)">OK</v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-flex>
                        <v-flex xs18 sm9 md6>
                          <v-text-field v-model="selectedItem.precio_entrada" :rules="precio_entradaRules" type="numeric" label="Precio de entrada" required></v-text-field>  
                        </v-flex>
                        <v-flex xs18 sm9 md6>
                          <v-menu
                            ref="fecha_salida"
                            v-model="fecha_salida"
                            :close-on-content-click="false"
                            :return-value.sync="selectedItem.fecha_salida"
                            transition="scale-transition"
                            offset-y
                            full-width
                            min-width="290px"
                          >
                            <template v-slot:activator="{ on }">
                              <v-text-field
                                v-model="selectedItem.fecha_salida"
                                label="Fecha de salida"
                                prepend-icon="event"
                                readonly
                                v-on="on"
                              ></v-text-field>
                            </template>
                            <v-date-picker  locale="es-419" v-model="selectedItem.fecha_salida" no-title scrollable>
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="fecha_salida = false">Cancel</v-btn>
                              <v-btn text color="primary" @click="$refs.fecha_salida.save(selectedItem.fecha_salida)">OK</v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-flex>
                        <v-flex xs18 sm9 md6>
                          <v-text-field v-model="selectedItem.precio_salida" type="numeric" label="Precio de salida" required></v-text-field>  
                        </v-flex>
                        <v-flex xs18 sm9 md6>
                            <v-autocomplete
                              v-model="selectedItem.cliente"
                              :items="comboboxes.fields.clientes"
                              :loading="isLoading"
                              :search-input.sync="searchAutoClientes"
                              color="white"
                              hide-no-data
                              hide-selected
                              item-text="nombre"
                              item-value="id"
                              no-filter
                              label="Cliente:"
                              placeholder="Escribir para buscar"
                              prepend-icon="mdi-database-search"
                              return-object
                            >
                               <template v-slot:item="data">
                                  <v-list-item-content>
                                    <v-list-item-title v-html="data.item.nombre + ' '+ data.item.apellido"></v-list-item-title>
                                    <v-list-item-subtitle v-html="'DNI:' + data.item.documento"></v-list-item-subtitle>
                                    <v-list-item-subtitle v-html="'Email:' + data.item.email"></v-list-item-subtitle>
                                  </v-list-item-content>
                              </template>
                            </v-autocomplete>
                            </v-flex>
                        <v-flex xs36 sm18 md12 v-if="serializado == true">
                          <v-combobox
                            v-model="selectedItem.seriales"
                            :items="comboboxes.fields.seriales"
                            :search-input.sync="comboboxes.searching.seriales"
                            hide-selected
                            small-chips
                            multiple
                            hint="Introduzca todos los seriales del mismo producto"
                            label="Seriales"
                            persistent-hint
                            :rules="serialesRules"
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
                          <!--<v-combobox
                            v-model="selectedItem.serial"
                            :items="comboboxes.fields.seriales"
                            :search-input.sync="comboboxes.searching.seriales"
                            hide-selected
                            hint="Introduzca todos los seriales del mismo producto"
                            label="Seriales"
                            persistent-hint
                            :rules="serialesRules"
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
                          </v-combobox>-->
                        </v-flex>
                        
                        
                          </v-layout>
                        </v-container>
                      </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                    <v-btn :disabled="!valid" color="success" text @click="save">Save</v-btn>
                  </v-card-actions>
                </v-form>
            </v-card>
            <!--<v-card v-else>
                <v-card-title>
                  <span class="headline">¿Seguro que quiere eliminar el producto?</span>
                </v-card-title>
              <v-card-text>

                <v-simple-table
                  dense
                  fixed-header
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
                      <td>Serial:</td>
                      <td>{{ selectedItem.serial }}</td>
                    </tr>
                    <tr>
                      <td>Tipo de producto:</td>
                      <td>{{ selectedItem.tipo }}</td>
                    </tr>
                    <tr>
                      <td>Código de barras:</td>
                      <td>{{ selectedItem.codbarras }}</td>
                    </tr>
                    <tr>
                      <td>Proveedor:</td>
                      <td>{{ selectedItem.proveedor}}</td>
                    </tr>
                    <tr>
                      <td>Fecha de entrada:</td>
                      <td>{{ selectedItem.fecha_entrada }}</td>
                    </tr>
                    <tr>
                      <td>Precio de entrada:</td>
                      <td>{{ selectedItem.precio_entrada }}</td>
                    </tr>
                    <tr>
                      <td>Fecha de salida:</td>
                      <td>{{ selectedItem.fecha_salida }}</td>
                    </tr>
                    <tr>
                      <td>Precio de salida:</td>
                      <td>{{ selectedItem.precio_salida }}</td>
                    </tr>
                  </tbody>
                </v-simple-table>
              </v-card-text>
              <template>
                <v-spacer></v-spacer>
              </template>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                <v-btn color="blue darken-1" text @click="save">Save</v-btn>
              </v-card-actions>
            </v-card>-->
          </v-dialog>
</template>
<script>
    export default {
        components:
        {

        },
        computed:
        {
          serializado: function()
          {
            if(this.selectedItem.codbarras)
            {
              if(this.selectedItem.codbarras.serializado == true)
              {
                return true
              }
            }
            return false
          }
        },
        data()
        {
          return {
            dialog: false,
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
            /*fecha_salidaRules: [
              v => !!v || 'Fecha de salida requerida'
            ],*/
            defaultItem: {
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
            fecha_entrada: false,
            fecha_salida: false,
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
            searchAuto: null,
            searchAutoClientes: null,
            valid: true,
            isLoading: false,
            comboboxes:
            {
              fields:{
                codbarras: [],
                tipos: [],
                marcas: [],
                proveedores: [],
                seriales: [],
                clientes: []
              },
              searching:{
                codbarras: null,
                tipo: null,
                marca: null,
                proveedor: null,
                seriales: null
              },
              loading:
              {
                proveedores: false,
                clientes: false
              }
            },
          }
        },
        methods:
        {
          newItem ()
          {
            //this.formTitle = "Nuevo producto"
            this.selectedItem = Object.assign({}, this.defaultItem)
            this.dialog = true  
          },
          close () {
            this.dialog = false
            setTimeout(() => {
              this.selectedItem = Object.assign({}, this.defaultItem)
            //  this.editedIndex = -1
              
            }, 300)
          },
          save() 
          {
            axios.post('/admin/stock/nuevo',
                this.selectedItem).then(response => {
                  
                });
            this.close();
          }
        },
        mounted()
        {
          
          
            console.log(this.$parent.data());
          
        },
        watch:
        {

          searchAuto (val) {
            this.isLoading = true
            axios.post('/ajax/codbarras',
              {
                search:val
              }).then(response => {
                this.comboboxes.fields.codbarras = response.data;
                this.isLoading = false;
            });
          },
          searchAutoClientes(val)
          {
            this.comboboxes.loading.clientes = true
            axios.post('/ajax/clientes',
            {
              search: val
            }).then(response =>{
              this.comboboxes.fields.clientes = response.data;
              this.isLoadingClientes = false;
            });
          },
          'comboboxes.searching.proveedor'(val)
          {
            this.comboboxes.loading.proveedores = true
            axios.post('/ajax/proveedores',
                  {
                    search: val  
                  }).then(response => {
                    this.comboboxes.fields.proveedores = response.data;
                    this.comboboxes.loading.proveedores = false
                  });
          }
        }
    }
</script>