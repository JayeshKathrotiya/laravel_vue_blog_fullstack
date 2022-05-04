import Vue from "vue";
import vuex from "vuex";
Vue.use(vuex);

export default new vuex.Store({
    state: {
        counter : 1000,
    },
    getters: {
        getCounter(state){
            return state.counter;
        },
    },
    mutations: {
        changeCounter(state, payload){
            state.counter += payload;
        }
    },
    actions: {  
        changeCounterAction({commit}, payload){
            commit('changeCounter', payload);
        }
    }
});