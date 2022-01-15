<template>
    <div>
        <v-card outlined>
            <v-card-title class="pb-0 mb-0 grey lighten-4">
                <h3 class="text-muted mb-3 d-sm-flex d-md-none ml-auto mr-auto">Permisos</h3>
                <v-row class="fill-height" no-gutters>
                    <v-col cols="12" class="d-flex mb-2">
                        <h3 class="text-muted mt-1 mr-2 d-none d-md-flex d-sm-none d-md-flex">Permisos</h3>
                        <v-text-field
                        v-model.lazy="search"
                        @keyup.native="getListarPermissions(), resetPaginacion()"
                        append-icon="mdi-magnify"
                        label="Buscar permisos"
                        dense
                        hide-details
                        outlined>
                        </v-text-field>

                        <template v-if="permissionsAuthUser.includes('permissions.create')">
                            <v-btn color="primary" class="ml-2 button_add d-none d-sm-none d-md-flex" @click="openModal"
                                >AGREGAR PERMISO
                                <v-icon class="ml-1">mdi-arrow-right</v-icon>
                            </v-btn>
                        </template>

                        <v-tooltip left>
                            <template v-if="permissionsAuthUser.includes('permissions.create')" v-slot:activator="{ on, attrs }">
                                <v-btn color="primary" class=" ml-2 button_add d-sm-flex d-md-none" v-bind="attrs" v-on="on" @click="openModal">
                                    <v-icon>mdi-plus-circle-outline</v-icon>
                                </v-btn>
                            </template>
                            <span>Agregar permiso</span>
                        </v-tooltip>
                    </v-col>
                </v-row>
            </v-card-title>

            <v-data-table
            item-key="id"
            class="elevation-1"
            :headers="headers"
            :header-props="{ sortByText: 'Ordenar por' }"
            :items="ContenedorPermissions"
            :options.sync="options"
            :server-items-length="totalContenedorPermissions"
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

                <template v-slot:[`item.id`]="{ item }">
                    <v-tooltip bottom>
                        <template v-if="permissionsAuthUser.includes('permissions.edit')" v-slot:activator="{ on, attrs }">
                            <v-icon small color="lime" class="mr-2" v-bind="attrs" v-on="on" @click.prevent="editPermission(item)">mdi-pencil</v-icon>
                        </template>
                        <span>Editar permiso</span>
                    </v-tooltip>

                    <v-tooltip bottom>
                        <template v-if="permissionsAuthUser.includes('permissions.delete')" v-slot:activator="{ on, attrs }">
                            <v-icon small color="red" v-bind="attrs" v-on="on" @click.prevent="deletePermission(item.id)">mdi-delete</v-icon>
                        </template>
                        <span>Eliminar permiso</span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog v-model="dialog" persistent max-width="650px" style="z-index: 9999;">
            <v-card>
                <v-card-title class="mb-2 grey lighten-4" style="border-bottom: 1px solid #B0BEC5 !important;">
                    <span v-show="!editionmode" class="headline">Agregar permiso</span>
                    <span v-show="editionmode" class="headline">Editar permiso</span>
                    <v-btn icon white outlined top absolute right @click.prevent="cleanFormulario">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>

                <v-card-text class="mt-4 pb-0">
                    <v-form>
                        <v-container>
                            <v-row>
                                <v-col sm="3" md="2" lg="2" xl="2" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Nombre:</v-subheader>
                                </v-col>
                                <v-col sm="9" md="10" lg="10" xl="10" class="my-0 py-0">
                                    <v-text-field v-model="form.name" label="Escriba el nombre del permiso" append-icon="fas fa-unlock-alt" dense outlined class="input_form icons_formularios my-0 py-0" :error-messages="errors.name" hint="Por ejemplo, create:order">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="3" md="2" lg="2" xl="2" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Descripción:</v-subheader>
                                </v-col>
                                <v-col sm="9" md="10" lg="10" xl="10" class="my-0 py-0">
                                    <v-text-field v-model="form.description" label="Escriba la descripción del permiso" append-icon="fas fa-unlock-alt" dense outlined class="input_form icons_formularios my-0 py-0" :error-messages="errors.description" hint="Por ejemplo, Crear orden">
                                    </v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-form>
                </v-card-text>

                <v-card-actions class="mt-0 pt-0 py-3">
                    <v-btn color="primary" v-show="!editionmode" block :disabled="in_submit" @click.prevent="addPermission">
                        <v-icon left>mdi-content-save</v-icon>
                        Agregar permiso
                    </v-btn>

                    <v-btn color="primary" v-show="editionmode" block :disabled="in_submit" @click.prevent="updatePermission">
                        <v-icon left>mdi-content-save</v-icon>
                        Actualizar permiso
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
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
                guard_name: 'sanctum'
            },

            headers: [
                {text: 'Nombre del permiso', value: 'name'},
                {text: 'Descripción del permiso', value: 'description'},
                {text: 'Creación del permiso', value: 'created_at'},
                {text: 'Acciones', value: 'id', sortable: false},
            ],

            ContenedorPermissions: [],
            editionmode: false,
            loading: false,
            dialog: false,
            in_submit: false,
            options: {},
            totalContenedorPermissions: 0,
            errors: '',
            permissionsAuthUser: JSON.parse(localStorage.getItem('permissions')),
        }
    },

    watch: {
        options: {
            handler(){
                this.getListarPermissions();
            },
            deep: true,
        },
    },

    methods: {
        getListarPermissions(){
            let me = this;
            me.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = me.options
            axios.get('/api/permissions', {
                params: {
                    sortBy: sortBy[0],
                    sortDesc: sortDesc[0],
                    page: page,
                    itemsPerPage: itemsPerPage,
                    search: me.search,
                }
            }).then(response => {
                me.ContenedorPermissions = response.data.permissions.data;
                me.totalContenedorPermissions = response.data.permissions.total;
                me.loading = false;
            }).catch(error => {
                if(error.response.status == 401){
                    me.$router.push({name: 'login.index'});
                    location.reload();
                    localStorage.clear();
                }
            });
        },

        addPermission(){
            let me = this;
            me.in_submit = true;
            axios.post('/api/permissions', {
                'name': me.form.name,
                'description': me.form.description,
                'guard_name': me.form.guard_name,
            }).then( () =>{
                me.in_submit = false;
                me.$notify({
                    title: 'Exitoso!',
                    message: 'Se ha creado el permiso',
                    type: 'success',
                    duration: 2000
                });
                Fire.$emit('afterCrearPermisos');
            }).catch(error =>{
                me.in_submit = false;
                me.errors = error.response.data.errors;
                if(error.response.status == 401){
                    me.$router.push({name: 'login.index'});
                    location.reload();
                    localStorage.clear();
                }
            });
        },

        editPermission(item){
            let me = this;
            me.dialog = true;
            me.editionmode = true;
            me.form.id = item.id;
            me.form.name = item.name;
            me.form.description = item.description;
        },

        updatePermission(){
            let me = this;
            me.in_submit = true;
            axios.put('/api/permissions/'+ me.form.id, {
                'name': me.form.name,
                'description': me.form.description,
                'guard_name': me.form.guard_name,
            }).then( () =>{
                me.in_submit = false;
                me.$notify({
                    title: 'Exitoso!',
                    message: 'Se ha actualizado el permiso',
                    type: 'success',
                    duration: 2000
                });
                Fire.$emit('afterCrearPermisos');
            }).catch(error =>{
                me.in_submit = false;
                me.errors = error.response.data.errors;
                if(error.response.status == 401){
                    me.$router.push({name: 'login.index'});
                    location.reload();
                    localStorage.clear();
                }
            });
        },

        deletePermission(id){
            let me = this;
            me.$confirm('No podrás revertir esto!', '¿Está seguro?', {
                confirmButtonText: 'Si, Eliminar',
                cancelButtonText: 'Cancelar',
                type: 'warning',
                closeOnClickModal: false,
                closeOnPressEscape: true
            }).then(() => {
                axios.delete('/api/permissions/' + id)
                .then(()=>{
                    me.getListarPermissions();
                    me.$notify({
                        title: 'Exitoso!',
                        message: 'Se ha eliminado el permiso',
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

        openModal(){
            this.dialog = true;
            this.editionmode = false;
        },

        resetPaginacion(){
            this.options.page = 1;
        },

        cleanFormulario(){
            Fire.$emit('afterCrearPermisos');
        },

    },

    created(){
        Fire.$on('afterCrearPermisos', () => {
            let me = this;
            me.getListarPermissions();
            me.form.id = null;
            me.form.name = null;
            me.form.description = null;
            me.errors = false;
            me.dialog = false;
        });
    }
}
</script>
