import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);
const stockStore = new Vuex.Store({
    state: {
        action: null,
    	search: null,
        disponible: "todos",
        dialog: false,
        runSearch: true,
        item: null
    },
    mutations: {
        setAction(state,n)
        {
            state.action = n
        },
        setItem(state, n)
        {
            state.item = n
        },
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
        }
    },
    getters: {
        getAction: state => {
            return state.action
        },
        getItem: state => {
            return state.item
        },
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
        }
	},
    actions: {
       	
    }
});	
export default stockStore;