<template>
    <div>
        <v-card outlined>
            <v-card-title class="pb-0 mb-0 grey lighten-4">
                <h3 class="text-muted mb-3 d-sm-flex d-md-none ml-auto mr-auto">Marcas</h3>
                <v-row class="fill-height" no-gutters>
                    <v-col cols="12" class="d-flex mb-2">
                        <h3 class="text-muted mt-1 mr-2 d-none d-md-flex d-sm-none d-md-flex">Marcas</h3>
                        <v-text-field
                        v-model.lazy="search"
                        @keyup.native="getListarMarcas(), resetPaginacion()"
                        append-icon="mdi-magnify"
                        label="Buscar marcas"
                        dense
                        hide-details
                        outlined>
                        </v-text-field>

                        <template v-if="permissionsAuthUser.includes('marca.create')">
                            <v-btn color="primary" class="ml-2 button_add d-none d-sm-none d-md-flex" @click="openModal">
                                AGREGAR MARCA<v-icon class="ml-1">mdi-arrow-right</v-icon>
                            </v-btn>
                        </template>


                        <v-tooltip left>
                            <template v-if="permissionsAuthUser.includes('marca.create')" v-slot:activator="{ on, attrs }">
                                <v-btn color="primary" class=" ml-2 button_add d-sm-flex d-md-none" v-bind="attrs" v-on="on" @click="openModal">
                                    <v-icon>mdi-plus-circle-outline</v-icon>
                                </v-btn>
                            </template>
                            <span>Agregar Marca</span>
                        </v-tooltip>
                    </v-col>
                </v-row>
            </v-card-title>

            <v-data-table
            item-key="id"
            class="elevation-1"
            :headers="headers"
            :header-props="{ sortByText: 'Ordenar por' }"
            :items="ContenedorMarcas"
            :options.sync="options"
            :server-items-length="totalContenedorMarcas"
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


                <template v-slot:[`item.roles.name`]="{ item }">
                    <div class="d-flex flex-column">
                        <span v-for="(role, index) in item.roles" :key="index">
                            {{ role.name }}
                        </span>
                    </div>
                </template>

                <template v-slot:[`item.id`]="{ item }">
                    <v-tooltip bottom>
                        <template v-if="permissionsAuthUser.includes('marca.edit')" v-slot:activator="{ on, attrs }">
                            <v-icon small color="lime" class="mr-2" v-bind="attrs" v-on="on" @click.prevent="editMarca(item)">mdi-pencil</v-icon>
                        </template>
                        <span>Editar marca</span>
                    </v-tooltip>

                    <v-tooltip bottom>
                        <template v-if="permissionsAuthUser.includes('marca.delete')" v-slot:activator="{ on, attrs }">
                            <v-icon small color="red" v-bind="attrs" v-on="on" @click.prevent="deleteUser(item)">mdi-delete</v-icon>
                        </template>
                        <span>Eliminar marca</span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog v-model="dialog" scrollable persistent max-width="650px" style="z-index: 9999;">
            <v-card>
                <v-card-title class="grey lighten-4" style="border-bottom: 1px solid #B0BEC5 !important;">
                    <span v-show="!editionmode" class="headline">Agregar marca</span>
                    <span v-show="editionmode" class="headline">Editar marca</span>
                    <v-btn icon white outlined top absolute right @click.prevent="cleanFormulario">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>

                <v-card-text class="mt-4 pb-0">
                    <v-form class="mt-2">
                        <v-container>
                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Nombre:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.casa_marca" label="Escriba el nombre de la marca" append-icon="fas fa-car" dense outlined class="input_form icons_formularios my-0 py-0" :error-messages="errors.casa_marca" hint="Por ejemplo, Chevrolet">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                        </v-container>
                    </v-form>
                </v-card-text>

                <v-card-actions class="mt-0 pt-0 py-3">
                    <v-btn color="primary" v-show="!editionmode" block :disabled="in_submit" @click.prevent="addMarca">
                        <v-icon left>mdi-content-save</v-icon>
                        Agregar marca
                    </v-btn>

                    <v-btn color="primary" v-show="editionmode" block :disabled="in_submit" @click.prevent="updateMarca">
                        <v-icon left>mdi-content-save</v-icon>
                        Actualizar marca
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>

import {mask} from 'vue-the-mask'

export default {
    directives: {
        mask
    },

    data(){
        return {

            search: '',

            form: {
                id: '',
                casa_marca: ''
            },

            tipo_posicion: [
                {item: 'Delantera', text: 'Delantera'},
                {item: 'Trasera', text: 'Trasera'}
            ],


            headers: [
                {text: 'Nombre de la marca', value: 'casa_marca'},
                {text: 'Fecha de creación', value: 'created_at'},
                {text: 'Acciones', value: 'id', sortable: false},
            ],

            ContenedorMarcas: [],
            editionmode: false,
            loading: false,
            dialog: false,
            in_submit: false,
            options: {},
            totalContenedorMarcas: 0,
            errors: '',
            permissionsAuthUser: JSON.parse(localStorage.getItem('permissions'))
        }
    },

    watch: {
        options: {
            handler(){
                this.getListarMarcas();
            },
            deep: true,
        },
    },

    methods: {
        getListarMarcas(){
            let me = this;
            me.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = me.options
            axios.get('/api/marcas', {
                params: {
                    sortBy: sortBy[0],
                    sortDesc: sortDesc[0],
                    page: page,
                    itemsPerPage: itemsPerPage,
                    search: me.search,
                }
            }).then(response => {
                me.ContenedorMarcas = response.data.marca.data;
                me.totalContenedorMarcas = response.data.marca.total;
                me.loading = false;
            }).catch(error => {
                if(error.response.status == 401){
                    me.$router.push({name: 'login.index'});
                    location.reload();
                    localStorage.clear();
                }
            });
        },

        addMarca(){
            let me = this;
            me.in_submit = true;
            axios.post('/api/marcas', {
                'casa_marca': me.form.casa_marca,
            }).then(() =>{
                me.in_submit = false;
                me.$notify({
                    title: 'Exitoso!',
                    message: 'Marca registrada!',
                    type: 'success',
                    duration: 1500
                });
                Fire.$emit('afterCrearMarca');
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

        editMarca(item){
            let me = this;
            me.dialog = true;
            me.editionmode = true;
            me.form.id = item.id;
            me.form.casa_marca = item.casa_marca;
        },

        updateMarca(){
            let me = this;
            me.in_submit = true;
            axios.put('/api/marcas/'+ me.form.id, {
                'casa_marca': me.form.casa_marca,
            }).then(() => {
                me.in_submit = false;
                me.$notify({
                    title: 'Exitoso!',
                    message: 'Marca actualizada!',
                    type: 'success',
                    duration: 1500
                });
                Fire.$emit('afterCrearMarca');
            }).catch(error => {
                me.in_submit = false;
                me.errors = error.response.data.errors;
                if(error.response.status == 401){
                    me.$router.push({name: 'login.index'});
                    location.reload();
                    localStorage.clear();
                }
            })
        },

        deleteUser(item){
            let me = this;
            me.$confirm('No podrás revertir esto!', '¿Está seguro?', {
                confirmButtonText: 'Si, Eliminar',
                cancelButtonText: 'Cancelar',
                type: 'warning',
                closeOnClickModal: false,
                closeOnPressEscape: true
            }).then(() => {
                axios.delete('/api/marcas/' + item.id)
                .then(()=>{
                    me.getListarMarcas();
                    me.$notify({
                        title: 'Exitoso!',
                        message: 'Se ha eliminado la marca',
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

        cleanFormulario(){
            Fire.$emit('afterCrearMarca');
        },

        resetPaginacion(){
            this.options.page = 1;
        },
    },

    created(){
        Fire.$on('afterCrearMarca', () => {
            let me = this;
            me.getListarMarcas();
            me.form.id = null;
            me.form.casa_marca = null;
            me.errors = false;
            me.dialog = false;
        });
    }

}
</script>
