<template>
    <div>
        <v-card outlined>
            <v-card-title class="pb-0 mb-0 grey lighten-4">
                <h3 class="text-muted mb-3 d-sm-flex d-md-none ml-auto mr-auto">Piezas</h3>
                <v-row class="fill-height" no-gutters>
                    <v-col cols="12" class="d-flex mb-2">
                        <h3 class="text-muted mt-1 mr-2 d-none d-md-flex d-sm-none d-md-flex">Piezas</h3>
                        <v-text-field
                        v-model.lazy="search"
                        @keyup.native="getListarPiezas(), resetPaginacion()"
                        append-icon="mdi-magnify"
                        label="Buscar registro"
                        dense
                        hide-details
                        outlined>
                        </v-text-field>

                        <template v-if="permissionsAuthUser.includes('dashboard.create')">
                            <v-btn color="primary" class="ml-2 button_add d-none d-sm-none d-md-flex" @click="openModal">
                                AGREGAR REGISTRO<v-icon class="ml-1">mdi-arrow-right</v-icon>
                            </v-btn>
                        </template>


                        <v-tooltip left>
                            <template v-if="permissionsAuthUser.includes('dashboard.create')" v-slot:activator="{ on, attrs }">
                                <v-btn color="primary" class=" ml-2 button_add d-sm-flex d-md-none" v-bind="attrs" v-on="on" @click="openModal">
                                    <v-icon>mdi-plus-circle-outline</v-icon>
                                </v-btn>
                            </template>
                            <span>Agregar registro</span>
                        </v-tooltip>
                    </v-col>
                </v-row>
            </v-card-title>

            <v-data-table
            item-key="id"
            class="elevation-1"
            :headers="headers"
            :header-props="{ sortByText: 'Ordenar por' }"
            :items="ContenedorRegistros"
            :options.sync="options"
            :server-items-length="totalContenedorRegistros"
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
                        <template v-if="permissionsAuthUser.includes('dashboard.edit')" v-slot:activator="{ on, attrs }">
                            <v-icon small color="lime" class="mr-2" v-bind="attrs" v-on="on" @click.prevent="editUser(item)">mdi-pencil</v-icon>
                        </template>
                        <span>Editar registro</span>
                    </v-tooltip>

                    <v-tooltip bottom>
                        <template v-if="permissionsAuthUser.includes('dashboard.delete')" v-slot:activator="{ on, attrs }">
                            <v-icon small color="red" v-bind="attrs" v-on="on" @click.prevent="deleteRegistro(item)">mdi-delete</v-icon>
                        </template>
                        <span>Eliminar registro</span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog v-model="dialog" scrollable persistent max-width="650px" style="z-index: 9999;">
            <v-card>
                <v-card-title class="grey lighten-4" style="border-bottom: 1px solid #B0BEC5 !important;">
                    <span v-show="!editionmode" class="headline">Agregar registro</span>
                    <span v-show="editionmode" class="headline">Editar registro</span>
                    <v-btn icon white outlined top absolute right @click.prevent="cleanFormulario">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>

                <v-card-text class="mt-4 pb-0">
                    <v-form class="mt-2">
                        <v-container>
                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Repuesto:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-select v-model="form.repuestofreno_id" label="Seleccione el tipo de repuesto" :items="ContenedorRepuestos"
                                    :item-text="item =>`${item.codigo} - ${item.descripcion} - ${item.compatibilidad}`" item-value="id" clearable dense outlined append-icon="far fa-list-alt" class="input_form icons_formularios input_form_select my-0 py-0"
                                    :error-messages="errors.repuestofreno_id" hint="Por ejemplo, d1475d" >
                                    </v-select>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Marca vehiculo:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-select v-model="form.casa_marca_id" label="Seleccione el tipo de marca del vehiculo" :items="ContenedorMarcas"
                                    item-text="casa_marca" item-value="id" clearable dense outlined append-icon="far fa-list-alt" class="input_form icons_formularios input_form_select my-0 py-0"
                                    :error-messages="errors.casa_marca_id" hint="Por ejemplo, Chevrolet" >
                                    </v-select>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Modelo:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.modelo" label="Escriba el modelo del vehiculo" append-icon="fas fa-car" dense outlined class="input_form icons_formularios my-0 py-0" :error-messages="errors.modelo" hint="Por ejemplo, Accent">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Año:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.anio_vehiculo" label="Escriba el año del vehiculo" append-icon="fas fa-car" dense outlined class="input_form icons_formularios my-0 py-0" :error-messages="errors.anio_vehiculo" hint="Por ejemplo, 2011">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                        </v-container>
                    </v-form>
                </v-card-text>

                <v-card-actions class="mt-0 pt-0 py-3">
                    <v-btn color="primary" v-show="!editionmode" block :disabled="in_submit" @click.prevent="addRegistro">
                        <v-icon left>mdi-content-save</v-icon>
                        Agregar registro
                    </v-btn>

                    <v-btn color="primary" v-show="editionmode" block :disabled="in_submit" @click.prevent="updateRegistro">
                        <v-icon left>mdi-content-save</v-icon>
                        Actualizar registro
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
                repuestofreno_id: '',
                casa_marca_id: '',
                modelo: '',
                anio_vehiculo: ''
            },


            headers: [
                {text: 'Casa Marca', value: 'casa_marca'},
                {text: 'Modelo', value: 'modelo'},
                {text: 'Año', value: 'anio_vehiculo'},
                {text: 'Código', value: 'codigo'},
                {text: 'Clase', value: 'clase'},
                {text: 'Medidas', value: 'medidas'},
                {text: 'Posicion', value: 'posicion'},
                {text: 'Descripción', value: 'descripcion'},
                {text: 'Compatibilidad', value: 'compatibilidad'},
                {text: 'Acciones', value: 'id', sortable: false},
            ],

            ContenedorRegistros: [],
            ContenedorMarcas: [],
            ContenedorRepuestos: [],
            editionmode: false,
            loading: false,
            dialog: false,
            in_submit: false,
            options: {},
            totalContenedorRegistros: 0,
            errors: '',
            permissionsAuthUser: JSON.parse(localStorage.getItem('permissions'))
        }
    },

    watch: {
        options: {
            handler(){
                this.getListarPiezas();
            },
            deep: true,
        },
    },

    methods: {
        getListarPiezas(){
            let me = this;
            me.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = me.options
            axios.get('/api/vehiculos', {
                params: {
                    sortBy: sortBy[0],
                    sortDesc: sortDesc[0],
                    page: page,
                    itemsPerPage: itemsPerPage,
                    search: me.search,
                }
            }).then(response => {
                me.ContenedorRegistros = response.data.vehiculo.data;
                me.totalContenedorRegistros = response.data.vehiculo.total;
                me.ContenedorRepuestos = response.data.repuesto;
                me.ContenedorMarcas = response.data.marca;
                me.loading = false;
            }).catch(error => {
                if(error.response.status == 401){
                    me.$router.push({name: 'login.index'});
                    location.reload();
                    localStorage.clear();
                }
            });
        },

        addRegistro(){
            let me = this;
            me.in_submit = true;
            axios.post('/api/vehiculos', {
                'repuestofreno_id': me.form.repuestofreno_id,
                'casa_marca_id': me.form.casa_marca_id,
                'modelo': me.form.modelo,
                'anio_vehiculo': me.form.anio_vehiculo,
            }).then(() =>{
                me.in_submit = false;
                me.$notify({
                    title: 'Exitoso!',
                    message: 'Registro guardado exitosamente!',
                    type: 'success',
                    duration: 1500
                });
                Fire.$emit('afterCrearRegistro');
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

        editUser(item){
            let me = this;
            me.dialog = true;
            me.editionmode = true;
            me.form.id = item.id;
            me.form.repuestofreno_id = item.repuestofreno_id;
            me.form.casa_marca_id = item.casa_marca_id;
            me.form.modelo = item.modelo;
            me.form.anio_vehiculo = item.anio_vehiculo;
        },

        updateRegistro(){
            let me = this;
            me.in_submit = true;
            axios.put('/api/vehiculos/'+ me.form.id, {
                'repuestofreno_id': me.form.repuestofreno_id,
                'casa_marca_id': me.form.casa_marca_id,
                'modelo': me.form.modelo,
                'anio_vehiculo': me.form.anio_vehiculo,
            }).then(() => {
                me.in_submit = false;
                me.$notify({
                    title: 'Exitoso!',
                    message: 'Registro actualizado!',
                    type: 'success',
                    duration: 1500
                });
                Fire.$emit('afterCrearRegistro');
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

        deleteRegistro(item){
            let me = this;
            me.$confirm('No podrás revertir esto!', '¿Está seguro?', {
                confirmButtonText: 'Si, Eliminar',
                cancelButtonText: 'Cancelar',
                type: 'warning',
                closeOnClickModal: false,
                closeOnPressEscape: true
            }).then(() => {
                axios.delete('/api/vehiculos/' + item.id)
                .then(()=>{
                    me.getListarPiezas();
                    me.$notify({
                        title: 'Exitoso!',
                        message: 'Se ha eliminado el registro',
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
            Fire.$emit('afterCrearRegistro');
        },

        resetPaginacion(){
            this.options.page = 1;
        },
    },

    created(){
        Fire.$on('afterCrearRegistro', () => {
            let me = this;
            me.getListarPiezas();
            me.form.id = null;
            me.form.repuestofreno_id = null;
            me.form.casa_marca_id = null;
            me.form.modelo = null;
            me.form.anio_vehiculo = null;
            me.errors = false;
            me.dialog = false;
        });
    }

}
</script>
