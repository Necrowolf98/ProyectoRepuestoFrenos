import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

function VerifyAccess(to, from, next){
    let token = localStorage.getItem('token');

    if(token) {
        let permissionsAuthUser = JSON.parse(localStorage.getItem('permissions'));

        if(permissionsAuthUser.includes(to.name)){
            next();
        }else{
            let permissionsAuthUserFilter = [];
            permissionsAuthUser.map(function(x){
                if(x.includes('index')){
                    permissionsAuthUserFilter.push(x);
                }
            });

            if (to.name == 'dashboard.index'){
                next({name: permissionsAuthUserFilter[0]});
            }else{
                next({name: permissionsAuthUserFilter[0]});
            }
        }
    }else{
        next('/')
    }
}


const routes = [
    {
        path: '/',
        name: 'login.index',
        component: require('./components/modules/authenticate/Index.vue').default,
        beforeEnter: (to, from, next)=>{
            let token = localStorage.getItem('token');
            if(token){
                next({name: 'dashboard.index'});
            }else{
                next();
            }
        }
    },

    {
        path: '*',
        component: require('./components/plantilla/404.vue').default
    },

    {
        path: '/dashboard',
        name: 'dashboard.index',
        component: require('./components/modules/dashboard/Index.vue').default,
        beforeEnter: (to, from, next)=>{
            VerifyAccess(to, from, next);
        }
    },

    {
        path: '/repuesto',
        name: 'repuesto.index',
        component: require('./components/modules/repuestofrenos/Index.vue').default,
        beforeEnter: (to, from, next)=>{
            VerifyAccess(to, from, next);
        }
    },

    {
        path: '/marca',
        name: 'marca.index',
        component: require('./components/modules/marcas/Index.vue').default,
        beforeEnter: (to, from, next)=>{
            VerifyAccess(to, from, next);
        }
    },

    {
        path: '/users',
        name: 'users.index',
        component: require('./components/modules/users/Index.vue').default,
        beforeEnter: (to, from, next) =>{
            VerifyAccess(to, from, next);
        }
    },
    {
        path: '/users/show/:id',
        name: 'users.show',
        component: require('./components/modules/users/Show.vue').default,
        props: true,
        beforeEnter: (to, from, next)=>{
            VerifyAccess(to, from, next);
        }
    },
    {
        path: '/users/permissions/:id',
        name: 'users.permissions',
        component: require('./components/modules/users/Permissions.vue').default,
        props: true,
        beforeEnter: (to, from, next)=>{
            VerifyAccess(to, from, next);
        }
    },
    {
        path: '/roles',
        name: 'roles.index',
        component: require('./components/modules/roles/Index.vue').default,
        beforeEnter: (to, from, next)=>{
            VerifyAccess(to, from, next);
        }
    },
    {
        path: '/roles/create',
        name: 'roles.create',
        component: require('./components/modules/roles/Create.vue').default,
        beforeEnter: (to, from, next)=>{
            VerifyAccess(to, from, next);
        }
    },
    {
        path: '/roles/edit/:id',
        name: 'roles.edit',
        component: require('./components/modules/roles/Edit.vue').default,
        props: true,
        beforeEnter: (to, from, next)=>{
            VerifyAccess(to, from, next);
        }
    },
    {
        path: '/permissions',
        name: 'permissions.index',
        component: require('./components/modules/permissions/Index.vue').default,
        beforeEnter: (to, from, next)=>{
            VerifyAccess(to, from, next);
        }
    }
];

const router = new Router({
    routes,
    mode: 'history',
    linkActiveClass: 'active',
});


export default router;

