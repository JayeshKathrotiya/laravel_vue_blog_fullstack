import Vue from "vue";
import Router from "vue-router";
Vue.use(Router);

// components
import firstPage from "./components/pages/myFirstVuePage.vue";
import newRoutePage from "./components/pages/newRoutePage.vue";
import hooks from "./components/pages/basic/hooks.vue";
import methods from "./components/pages/basic/methods.vue";

// project pages
import home from "./components/pages/home.vue";
import tags from "./admin/pages/tags.vue";
import category from "./admin/pages/category.vue";
import usecom from "./vuex/usecom.vue";

const routes = [
    // projects routes...
    {
        path: '/testvuex',
        component: usecom,
    },
    {
        path: '/',
        component: home,
        // name: 'home',
        // meta: {}
    },
    {
        path: '/tags',
        component: tags,
    },
    {
        path: '/category',
        component: category,
    },




    














    
    // basic tutorials routes
    {
        path: '/my-new-view-route',
        component: firstPage,
        // name: 'home',
        // meta: {}
    },
    {
        path: '/new-route',
        component: newRoutePage
    },

    // vue hooks
    {
        path: '/hooks',
        component: hooks
    },
    // more basics
    {
        path: '/methods',
        component: methods
    },
]

export default new Router({
    mode: 'history',
    routes
})