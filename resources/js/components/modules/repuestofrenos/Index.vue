<template>
    <div>
        <v-card outlined>
            <v-card-title class="pb-0 mb-0 grey lighten-4">
                <h3 class="text-muted mb-3 d-sm-flex d-md-none ml-auto mr-auto">Repuestos</h3>
                <v-row class="fill-height" no-gutters>
                    <v-col cols="12" class="d-flex mb-2">
                        <h3 class="text-muted mt-1 mr-2 d-none d-md-flex d-sm-none d-md-flex">Repuestos</h3>
                        <v-text-field
                        v-model.lazy="search"
                        @keyup.native="getListarRepuestos(), resetPaginacion()"
                        append-icon="mdi-magnify"
                        label="Buscar repuestos"
                        dense
                        hide-details
                        outlined>
                        </v-text-field>

                        <template v-if="permissionsAuthUser.includes('repuesto.create')">
                            <v-btn color="primary" class="ml-2 button_add d-none d-sm-none d-md-flex" @click="openModal">
                                AGREGAR REPUESTO<v-icon class="ml-1">mdi-arrow-right</v-icon>
                            </v-btn>
                        </template>


                        <v-tooltip left>
                            <template v-if="permissionsAuthUser.includes('repuesto.create')" v-slot:activator="{ on, attrs }">
                                <v-btn color="primary" class=" ml-2 button_add d-sm-flex d-md-none" v-bind="attrs" v-on="on" @click="openModal">
                                    <v-icon>mdi-plus-circle-outline</v-icon>
                                </v-btn>
                            </template>
                            <span>Agregar repuesto</span>
                        </v-tooltip>
                    </v-col>
                </v-row>
            </v-card-title>

            <v-data-table
            item-key="id"
            class="elevation-1"
            :headers="headers"
            :header-props="{ sortByText: 'Ordenar por' }"
            :items="ContenedorRepuestos"
            :options.sync="options"
            :server-items-length="totalContenedorRepuestos"
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
                        <template v-if="permissionsAuthUser.includes('repuesto.edit')" v-slot:activator="{ on, attrs }">
                            <v-icon small color="lime" class="mr-2" v-bind="attrs" v-on="on" @click.prevent="editRepuesto(item)">mdi-pencil</v-icon>
                        </template>
                        <span>Editar repuesto</span>
                    </v-tooltip>

                    <v-tooltip bottom>
                        <template v-if="permissionsAuthUser.includes('repuesto.delete')" v-slot:activator="{ on, attrs }">
                            <v-icon small color="red" v-bind="attrs" v-on="on" @click.prevent="deleteUser(item)">mdi-delete</v-icon>
                        </template>
                        <span>Eliminar repuesto</span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog v-model="dialog" scrollable persistent max-width="650px" style="z-index: 9999;">
            <v-card>
                <v-card-title class="grey lighten-4" style="border-bottom: 1px solid #B0BEC5 !important;">
                    <span v-show="!editionmode" class="headline">Agregar repuesto</span>
                    <span v-show="editionmode" class="headline">Editar repuesto</span>
                    <v-btn icon white outlined top absolute right @click.prevent="cleanFormulario">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>

                <v-card-text class="mt-4 pb-0">
                    <v-form class="mt-2">
                        <v-container>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Código:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.codigo" label="Escriba el código del repuesto" append-icon="fas fa-code" dense outlined class="input_form icons_formularios my-0 py-0" :error-messages="errors.codigo" hint="Por ejemplo, 10210">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Descripción:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.descripcion" label="Escriba la descripción del repuesto" append-icon="fas fa-list" dense outlined class="input_form icons_formularios my-0 py-0" :error-messages="errors.descripcion" hint="Por ejemplo, pastillas de encendido 5 pines">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Compatibilidad:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.compatibilidad" label="Escriba la compatibilidad" append-icon="fas fa-list" dense outlined class="input_form icons_formularios my-0 py-0" :error-messages="errors.compatibilidad" hint="Por ejemplo, compatibilidad A">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Clase:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.clase" label="Escriba la clase" append-icon="fas fa-list" dense outlined class="input_form icons_formularios my-0 py-0" :error-messages="errors.clase" hint="Por ejemplo, clase A">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Medidas:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.medidas" label="Escriba las medidas del repuesto" append-icon="fas fa-list" dense outlined class="input_form icons_formularios my-0 py-0" :error-messages="errors.medidas" hint="Por ejemplo, 5x3x2 cm">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Tipo de posición:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-select v-model="form.posicion" label="Seleccione el tipo de posición" :items="tipo_posicion"
                                    item-text="text" item-value="item" clearable dense outlined append-icon="far fa-list-alt" class="input_form icons_formularios input_form_select my-0 py-0"
                                    :error-messages="errors.posicion" hint="Por ejemplo, Delantera" >
                                    </v-select>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-form>
                </v-card-text>

                <v-card-actions class="mt-0 pt-0 py-3">
                    <v-btn color="primary" v-show="!editionmode" block :disabled="in_submit" @click.prevent="addRepuesto">
                        <v-icon left>mdi-content-save</v-icon>
                        Agregar repuesto
                    </v-btn>

                    <v-btn color="primary" v-show="editionmode" block :disabled="in_submit" @click.prevent="updateRepuesto">
                        <v-icon left>mdi-content-save</v-icon>
                        Actualizar repuesto
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
                codigo: '',
                descripcion: '',
                compatibilidad: '',
                clase: '',
                medidas: '',
                posicion: '',
                descripcion_id: ''
            },

            tipo_posicion: [
                {item: 'Delantera', text: 'Delantera'},
                {item: 'Trasera', text: 'Trasera'}
            ],


            headers: [
                {text: 'Código', value: 'codigo'},
                {text: 'Descripción', value: 'descripcion'},
                {text: 'Compatibilidad', value: 'compatibilidad'},
                {text: 'Clase', value: 'clase'},
                {text: 'Medidas', value: 'medidas'},
                {text: 'Posición', value: 'posicion'},
                {text: 'Acciones', value: 'id', sortable: false},
            ],

            ContenedorRepuestos: [],
            editionmode: false,
            loading: false,
            dialog: false,
            in_submit: false,
            options: {},
            totalContenedorRepuestos: 0,
            errors: '',
            permissionsAuthUser: JSON.parse(localStorage.getItem('permissions'))
        }
    },

    watch: {
        options: {
            handler(){
                this.getListarRepuestos();
            },
            deep: true,
        },
    },

    methods: {
        getListarRepuestos(){
            let me = this;
            me.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = me.options
            axios.get('/api/repuestofrenos', {
                params: {
                    sortBy: sortBy[0],
                    sortDesc: sortDesc[0],
                    page: page,
                    itemsPerPage: itemsPerPage,
                    search: me.search,
                }
            }).then(response => {
                me.ContenedorRepuestos = response.data.repuesto.data;
                me.totalContenedorRepuestos = response.data.repuesto.total;
                me.loading = false;
            }).catch(error => {
                if(error.response.status == 401){
                    me.$router.push({name: 'login.index'});
                    location.reload();
                    localStorage.clear();
                }
            });
        },

        addRepuesto(){
            let me = this;
            me.in_submit = true;
            axios.post('/api/repuestofrenos', {
                'codigo': me.form.codigo,
                'descripcion': me.form.descripcion,
                'compatibilidad': me.form.compatibilidad,
                'clase': me.form.clase,
                'medidas': me.form.medidas,
                'posicion': me.form.posicion,
            }).then(() =>{
                me.in_submit = false;
                me.$notify({
                    title: 'Exitoso!',
                    message: 'Repuesto registrado!',
                    type: 'success',
                    duration: 1500
                });
                Fire.$emit('afterCrearRepuesto');
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

        editRepuesto(item){
            let me = this;
            me.dialog = true;
            me.editionmode = true;
            me.form.id = item.id;
            me.form.codigo = item.codigo;
            me.form.descripcion = item.descripcion;
            me.form.compatibilidad = item.compatibilidad;
            me.form.clase = item.clase;
            me.form.medidas = item.medidas;
            me.form.posicion = item.posicion;
            me.form.descripcion_id = item.descripcion_id;
        },

        updateRepuesto(){
            let me = this;
            me.in_submit = true;
            axios.put('/api/repuestofrenos/'+ me.form.id, {
                'codigo': me.form.codigo,
                'descripcion': me.form.descripcion,
                'compatibilidad': me.form.compatibilidad,
                'clase': me.form.clase,
                'medidas': me.form.medidas,
                'posicion': me.form.posicion,
                'descripcion_id': me.form.descripcion_id
            }).then(() => {
                me.in_submit = false;
                me.$notify({
                    title: 'Exitoso!',
                    message: 'Repuesto actualizado!',
                    type: 'success',
                    duration: 1500
                });
                Fire.$emit('afterCrearRepuesto');
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
                axios.delete('/api/repuestofrenos/' + item.id, {
                    params: {
                        descripcion_id: item.descripcion_id
                    }
                }).then(()=>{
                    me.getListarRepuestos();
                    me.$notify({
                        title: 'Exitoso!',
                        message: 'Se ha eliminado el repuesto',
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
            Fire.$emit('afterCrearRepuesto');
        },

        resetPaginacion(){
            this.options.page = 1;
        },
    },

    created(){
        Fire.$on('afterCrearRepuesto', () => {
            let me = this;
            me.getListarRepuestos();
            me.form.id = null;
            me.form.codigo = null;
            me.form.descripcion = null;
            me.form.compatibilidad = null;
            me.form.clase = null;
            me.form.medidas = null;
            me.form.posicion = null;
            me.form.descripcion_id = null;
            me.errors = false;
            me.dialog = false;
        });
    }

}
</script>
