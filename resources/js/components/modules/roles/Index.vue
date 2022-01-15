<template>
    <div>
        <v-card outlined>
            <v-card-title class="pb-0 mb-0 grey lighten-4">
                <h3 class="text-muted mb-3 d-sm-flex d-md-none ml-auto mr-auto">Roles</h3>
                <v-row class="fill-height" no-gutters>
                    <v-col cols="12" class="d-flex mb-2">
                        <h3 class="text-muted mt-1 mr-2 d-none d-md-flex d-sm-none d-md-flex">Roles</h3>
                        <v-text-field
                        v-model.lazy="search"
                        @keyup.native="getListarRoles(), resetPaginacion()"
                        append-icon="mdi-magnify"
                        label="Buscar roles"
                        dense
                        hide-details
                        outlined>
                        </v-text-field>

                        <template v-if="permissionsAuthUser.includes('roles.create')">
                            <router-link to="/roles/create">
                                <v-btn color="primary" class="ml-2 button_add d-none d-sm-none d-md-flex"
                                    >AGREGAR ROL
                                    <v-icon class="ml-1">mdi-arrow-right</v-icon>
                                </v-btn>
                            </router-link>
                        </template>


                        <v-tooltip left>
                            <template v-if="permissionsAuthUser.includes('roles.create')" v-slot:activator="{ on, attrs }">
                                <router-link to="/roles/create">
                                        <v-btn color="primary" class=" ml-2 button_add d-sm-flex d-md-none" v-bind="attrs" v-on="on">
                                            <v-icon>mdi-plus-circle-outline</v-icon>
                                        </v-btn>
                                </router-link>
                            </template>
                            <span>Agregar rol</span>
                        </v-tooltip>
                    </v-col>
                </v-row>
            </v-card-title>

            <v-data-table
            item-key="id"
            class="elevation-1"
            :headers="headers"
            :header-props="{ sortByText: 'Ordenar por' }"
            :items="ContenedorRoles"
            :options.sync="options"
            :server-items-length="totalContenedorRoles"
            :loading="loading"
            no-data-text="Lo sentimos, no hay nada para mostrar aquí"
            dense
            sortByText="Ordenar por"
            loading-text="Cargando... Espere por favor"
            :footer-props="{
                'items-per-page-options' : [15, 25, 35, 45, 75, 150],
                'show-current-page': true,
                'show-first-last-page': true,
                'items-per-page-text': 'Filas por página',
            }">

                <template v-slot:[`item.permissions.name`]="{ item }">
                    <div class="d-flex flex-column">
                        <span v-for="(permission, index) in item.permissions" :key="index">
                            {{ permission.name }}
                        </span>
                    </div>
                </template>

                <template v-slot:[`item.id`]="{ item }">
                    <v-tooltip bottom>
                        <template v-if="permissionsAuthUser.includes('roles.edit')" v-slot:activator="{ on, attrs }">
                            <router-link :to="{ name: 'roles.edit', params: { id: item.id } }">
                                <v-icon small color="lime" class="mr-2" v-bind="attrs" v-on="on">mdi-pencil</v-icon>
                            </router-link>
                        </template>
                        <span>Editar rol</span>
                    </v-tooltip>

                    <v-tooltip bottom>
                        <template v-if="permissionsAuthUser.includes('roles.delete')" v-slot:activator="{ on, attrs }">
                            <v-icon small color="red" v-bind="attrs" v-on="on" @click.prevent="deleteRole(item.id)">mdi-delete</v-icon>
                        </template>
                        <span>Eliminar rol</span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </v-card>

    </div>
</template>

<script>
export default {

    data(){
        return {

            search: '',

            form: {
                id: '',
                name: '',
                description: '',
            },

            headers: [
                {text: 'Nombre del rol', value: 'name'},
                {text: 'Descripción del rol', value: 'description'},
                {text: 'Permisos del rol', value: 'permissions.name', sortable: false},
                {text: 'Creación del rol', value: 'created_at'},
                {text: 'Acciones', value: 'id', sortable: false},
            ],

            ContenedorRoles: [],
            loading: false,
            dialog: false,
            options: {},
            totalContenedorRoles: 0,
            errors: '',
            permissionsAuthUser: JSON.parse(localStorage.getItem('permissions')),
        }
    },

    watch: {
        options: {
            handler(){
                this.getListarRoles();
            },
            deep: true,
        },
    },

    methods: {
        getListarRoles(){
            let me = this;
            me.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = me.options
            axios.get('/api/roles', {
                params: {
                    sortBy: sortBy[0],
                    sortDesc: sortDesc[0],
                    page: page,
                    itemsPerPage: itemsPerPage,
                    search: me.search,
                }
            }).then(response => {
                me.ContenedorRoles = response.data.roles.data;
                me.totalContenedorRoles = response.data.roles.total;
                me.loading = false;
            }).catch(error => {
                if(error.response.status == 401){
                    me.$router.push({name: 'login.index'});
                    location.reload();
                    localStorage.clear();
                }
            });
        },

        deleteRole(id){
            let me = this;
            me.$confirm('No podrás revertir esto!', '¿Está seguro?', {
                confirmButtonText: 'Si, Eliminar',
                cancelButtonText: 'Cancelar',
                type: 'warning',
                closeOnClickModal: false,
                closeOnPressEscape: true
            }).then(() => {
                axios.delete('/api/roles/' + id)
                .then(()=>{
                    me.getListarRoles();
                    me.$notify({
                        title: 'Exitoso!',
                        message: 'Se ha eliminado el rol',
                        type: 'success',
                        duration: 1500
                    });
                }).catch(error => {
                    if(error.response.status == 401){
                        me.$router.push({name: 'login.index'});
                        location.reload();
                        localStorage.clear();
                    }
                    me.$notify({
                        title: 'Sucesión fallida!',
                        message: 'Oops.. algo salió mal',
                        type: 'error',
                        duration: 1500
                    });
                });
            }).catch(() => {
                me.$notify({
                    title: 'Cancelado',
                    type: 'info',
                    message: 'Eliminación cancelada',
                    duration: 1500
                });
            });
        },

        resetPaginacion(){
            this.options.page = 1;
        },
    },
}
</script>
