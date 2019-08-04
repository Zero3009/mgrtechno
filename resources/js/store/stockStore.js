import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);
const stockStore = new Vuex.Store({
    state: {
    	search: null,
        disponible: "todos",
        dialog: false,
        runSearch: true,
        selectedItem: {
            id: 0,
            codbarras: null,
            tipo: null,
            marca: null,
            modelo: null,
            proveedor: null,
            fecha_entrada: null,
            fecha_salida: null,
            precio_entrada: null,
            precio_salida: null,
            seriales: null,
            cliente: null
        },
    },
    mutations: {
        setRunSearch(state, n)
        {
            state.runSearch = n
        },
    	setSearch(state, n)
    	{
    		state.search = n
    	},
        setDisponible(state, n)
        {
            state.disponible = n
        },
        setDialog(state, n)
        {
            state.dialog = n
        },
        setSelectedItem(state, n)
        {
            state.selectedItem = n
        }
    },
    getters: {
        getRunSearch: state => {
            return state.runSearch
        },
		getSearch: state => {
			return state.search
		},
        getDisponible: state => {
            return state.disponible
        },
        getDialog: state => {
            return state.dialog
        },
        getSelectedItem: state => {
            return state.selectedItem
        }
	},
    actions: {
       	
    }
});	
export default stockStore;