<template>
	<div>
		<b-container fluid>
			<b-row>
				<b-col md="6" class="my-1">
					<b-form-group label-cols-sm="3" label="Filtar:" class="mb-0">
						<b-input-group>
							<b-form-input v-model="filter" placeholder="Buscar..."/>
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

			<b-table responsive striped hover bordered show-empty stacked="md" :items="myProvider" :fields="fields" :current-page="currentPage" :per-page="perPage" :filter="filter" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :sort-direction="sortDirection" @filtered="onFiltered" ref="table">
				<template slot="actions" slot-scope="row">
					<b-button variant="info" scope="item" v-on:click="editar(row.item)">
						<i class="fas fa-edit"></i>
					</b-button>
					<b-button variant="danger" scope="item" v-on:click="delet(row.item)"><i class="far fa-trash-alt"></i></b-button>
				</template>
			</b-table>
			<b-row>
		      <b-col md="6" class="my-1">
		        <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
		      </b-col>
	    	</b-row>
		</b-container>

		<!--<b-modal id="modal1" ref="delete" title="Eliminar registro">
			<template v-if="fordelete != null">
				<b-list-group>
					<b-list-group-item class="d-flex justify-content-between align-items-center">Serial:
						<b-badge variant="primary" pill>{{fordelete.serial}}</b-badge>
					</b-list-group-item>
					<b-list-group-item class="d-flex justify-content-between align-items-center">Marca:
						<b-badge variant="primary" pill>{{fordelete.marca}}</b-badge>
					</b-list-group-item>
					<b-list-group-item class="d-flex justify-content-between align-items-center">Modelo:
						<b-badge variant="primary" pill>{{fordelete.modelo}}</b-badge>
					</b-list-group-item>
				</b-list-group>
				<form method="POST" action="/admin/stock/eliminar" accept-charset="UTF-8" class="form-horizontal">
					<input type="hidden" name="id" :value="fordelete.id">
					<input type="hidden" name="_token" :value="csrf">
					<div class="d-flex justify-content-end" style="margin-top: 10px">
						<button class="btn btn-secondary" type="button" @click="hideModal" style="margin-right: 5px">Cancelar</button>
						<button class="btn btn-success" type="submit">Aceptar</button>
					</div>
				</form>
			</template>
			<div slot="modal-footer"></div>
  		</b-modal>-->
  		
  		<b-modal id="modal2" ref="edit" title="Editar registro">
			<template v-if="foredit != null">
				<form method="POST" action="/admin/stock/editar" accept-charset="UTF-8" class="form-horizontal">
					<!--<label for="select-codbarras">Código de barras:</label>
					<v-select :options="marcas" id="select-codbarras" v-model="selecteds.codbarras" placeholder="Código de barras"></v-select>
					<label for="select-prov">Marca:</label>
					<v-select :options="marcas" id="select-marcas" v-model="selecteds.marcas" placeholder="Proveedor"></v-select>
					
					<input type="hidden" name="id" :value="foredit.id">
					<input type="hidden" name="_token" :value="csrf">
					<div class="d-flex justify-content-end" style="margin-top: 10px">
						<button class="btn btn-secondary" type="button" @click="hideEdit" style="margin-right: 5px">Cancelar</button>
						<button class="btn btn-success" type="submit">Aceptar</button>
					</div>-->

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
	                            <label for="modelo">Modelo:</label><!--
	                            <v-select id="modelo" style="width: 100%" name="modelo" v-model="data.modelo" :options="modelos" required></v-select>-->
	                        </div>
	                    </div>
	                    <div class="form-group col-md-6">
	                        <div class="form-group">
	                            <label for="codbarras">Código de barras:</label>
	            				<input class="form-control" id="codbarras" stype="text" v-model="data.codbarras" name="codbarras" required>
	            				<template v-if="codbarrascheck = 'asd'">
	            					<small id="emailHelp" class="form-text text-primary">Correcto</small>    
                                </template>
	            				<template v-else>
                                        <small id="emailHelp" class="form-text text-danger">Complete serial para continuar</small>
                                </template>
                                <!--<template v-else-if="e">
                                        <small id="emailHelp" class="form-text text-primary">Correcto</small>
                                </template>-->
                                
                                 
	                        </div>
	                   </div>
	                </div>
	            </div>
	            <div class="d-flex justify-content-end" style="margin-top: 10px">
		            <button class="btn btn-secondary" type="button" @click="hideEdit" style="margin-right: 5px">Cancelar</button>
		            <button class="btn btn-success" type="submit">Aceptar</button>
		         </div>



				</form>



			</template>
			<div slot="modal-footer"></div>
  		</b-modal>
	</div>
</template>
<script>
	export default {
		data: function()
		{

			var datas =
			{
				csrf: $('meta[name=csrf-token]').attr('content'),
				data:
				{
					codbarras: null,
					marca: null,
					modelo: null,
					tipo: null
				},
				fordelete: null,
				foredit: null,
				item:null,
				searchText: "",
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
			codbarrascheck()
			{
				var response = "initializing";
				var send =this.data.codbarras;
				/*axios.post('/ajax/check/codbarras', {
					codbarras: send
				})
				.then(response => {

					response = response.data.length
					
				});*/
				return response;
			},
			marcas()
			{
				var marca = this.$parent.getMarcas();
				if(this.item != null)
				{
					for(var i = 0; i<marca.length;i++)
					{
						if(this.item.marca == marca[i])
						{
							this.data.marca = marca[i];
						}
					}
				}
				return marca;
			},
			tipos()
			{
				var tipo = this.$parent.getTipos()
				if(this.item != null)
				{
					for(var i = 0; i<tipo.length;i++)
					{
						if(this.item.tipo == tipo[i])
						{
							this.data.tipo = tipo[i];
						}
					}
				}
				return tipo;
			},
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
			onFiltered (filteredItems) {
		      // Trigger pagination to update the number of buttons/pages due to filtering
		      this.totalRows = filteredItems.length
		      this.currentPage = 1
		    },
		    editar(item)
		    {
		    	this.item = item;
		    	this.foredit = item;
		    	this.showEdit();
		    },
		    delete(item)
		    {
		    	this.item = item;
		    	this.fordelete = item;
		    },
		    hideEdit()
		    {
		    	this.$refs.edit.hide()
		    },
		    showEdit()
		    {
		    	this.$refs.edit.show()
		    }
		}
	}
</script>