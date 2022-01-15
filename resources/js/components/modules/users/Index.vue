<template>
    <div>
        <v-card outlined>
            <v-card-title class="pb-0 mb-0 grey lighten-4">
                <h3 class="text-muted mb-3 d-sm-flex d-md-none ml-auto mr-auto">Usuarios</h3>
                <v-row class="fill-height" no-gutters>
                    <v-col cols="12" class="d-flex mb-2">
                        <h3 class="text-muted mt-1 mr-2 d-none d-md-flex d-sm-none d-md-flex">Usuarios</h3>
                        <v-text-field
                        v-model.lazy="search"
                        @keyup.native="getListarUsuarios(), resetPaginacion()"
                        append-icon="mdi-magnify"
                        label="Buscar usuarios"
                        dense
                        hide-details
                        outlined>
                        </v-text-field>

                        <template v-if="permissionsAuthUser.includes('users.create')">
                            <v-btn color="primary" class="ml-2 button_add d-none d-sm-none d-md-flex" @click="openModal">
                                AGREGAR USUARIO<v-icon class="ml-1">mdi-arrow-right</v-icon>
                            </v-btn>
                        </template>


                        <v-tooltip left>
                            <template v-if="permissionsAuthUser.includes('users.create')" v-slot:activator="{ on, attrs }">
                                <v-btn color="primary" class=" ml-2 button_add d-sm-flex d-md-none" v-bind="attrs" v-on="on" @click="openModal">
                                    <v-icon>mdi-plus-circle-outline</v-icon>
                                </v-btn>
                            </template>
                            <span>Agregar usuario</span>
                        </v-tooltip>
                    </v-col>
                </v-row>
            </v-card-title>

            <v-data-table
            item-key="id"
            class="elevation-1"
            :headers="headers"
            :header-props="{ sortByText: 'Ordenar por' }"
            :items="ContenedorUsuarios"
            :options.sync="options"
            :server-items-length="totalContenedorUsuarios"
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
                        <template v-if="permissionsAuthUser.includes('users.show')" v-slot:activator="{ on, attrs }">
                            <router-link :to="{ name: 'users.show', params: { id: item.user_id } }">
                                <v-icon small color="primary" class="mr-2" v-bind="attrs" v-on="on">mdi-eye</v-icon>
                            </router-link>
                        </template>
                        <span>Ver usuario</span>
                    </v-tooltip>

                    <v-tooltip bottom>
                        <template v-if="permissionsAuthUser.includes('users.edit')" v-slot:activator="{ on, attrs }">
                            <v-icon small color="lime" class="mr-2" v-bind="attrs" v-on="on" @click.prevent="editUser(item)">mdi-pencil</v-icon>
                        </template>
                        <span>Editar usuario</span>
                    </v-tooltip>

                    <v-tooltip bottom>
                        <template v-if="permissionsAuthUser.includes('users.delete')" v-slot:activator="{ on, attrs }">
                            <v-icon small color="red" v-bind="attrs" v-on="on" @click.prevent="deleteUser(item)">mdi-delete</v-icon>
                        </template>
                        <span>Eliminar usuario</span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog v-model="dialog" scrollable persistent max-width="650px" style="z-index: 9999;">
            <v-card>
                <v-card-title class="grey lighten-4" style="border-bottom: 1px solid #B0BEC5 !important;">
                    <span v-show="!editionmode" class="headline">Agregar usuario</span>
                    <span v-show="editionmode" class="headline">Editar usuario</span>
                    <v-btn icon white outlined top absolute right @click.prevent="cleanFormulario">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>

                <v-card-text class="mt-4 pb-0">
                    <v-form class="mt-2">
                        <v-container>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Nombres:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.name" label="Escriba los nombres del usuario" append-icon="fas fa-user" dense outlined class="input_form icons_formularios my-0 py-0" :error-messages="errors.name" hint="Por ejemplo, Mauricio Antonio">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Apellidos:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.lastname" label="Escriba los apellidos del usuario" append-icon="fas fa-user" dense outlined class="input_form icons_formularios my-0 py-0" :error-messages="errors.lastname" hint="Por ejemplo, Cevallos Andrade">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Tipo de documento:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-select v-model="form.type_document" label="Seleccione el tipo de documento" :items="documentos"
                                    item-text="text" item-value="item" clearable dense outlined append-icon="far fa-list-alt" class="input_form icons_formularios input_form_select my-0 py-0"
                                    :error-messages="errors.type_document" hint="Por ejemplo, RUC" @change="Alertar">
                                    </v-select>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">N# de documento:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.number_document" @iput="(form.number_document == '') ? value => {form.number_document = value.toUpperCase()} : ''" label="Escriba el número de documento" append-icon="fas fa-fingerprint" dense outlined class="input_form icons_formularios my-0 py-0" hint="Por ejemplo, 1316881455" :error-messages="errors.number_document" v-mask="(form.type_document == 'DNI') ? '########A':  (form.type_document == 'RUC') ? '#############' : (form.type_document == 'C.I') ? '##########' : ''" :readonly="(form.type_document) ? false : true" @focus="Alertar">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Dirección:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.direction" label="Escriba la dirección del usuario" append-icon="fas fa-home" dense outlined class="input_form icons_formularios my-0 py-0" hint="Por ejemplo, Las neibas" :error-messages="errors.direction">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Teléfono:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.phone" label="Escriba el número de teléfono del usuario" append-icon="fas fa-phone-alt" dense outlined class="input_form icons_formularios my-0 py-0" hint="Por ejemplo, 098 773 4128" :error-messages="errors.phone" v-mask="'### ### ####'">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Correo:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.email" label="Escriba el correo del usuario" append-icon="fas fa-at" dense outlined class="input_form icons_formularios my-0 py-0" hint="Por ejemplo, correo@gmail.com" :error-messages="errors.email">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Usuario:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.username" label="Escriba el usuario para iniciar sesión" append-icon="fas fa-user-circle" dense outlined class="input_form icons_formularios my-0 py-0" hint="erickmero19" :error-messages="errors.username">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Clave:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.password" label="Escriba la clave del usuario" :append-icon="show_password ?  'mdi-eye' : 'mdi-eye-off'" @click:append="show_password = !show_password" :type="show_password ? 'text' : 'password'" dense outlined class="input_form icons_formularios my-0 py-0" hint="Escriba su clave"
                                    :error-messages="errors.password">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Confirmar clave:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-text-field v-model="form.password_confirmation" label="Confirme su clave del usuario" :append-icon="show_comfirmpassword ?  'mdi-eye' : 'mdi-eye-off'" @click:append="show_comfirmpassword = !show_comfirmpassword" :type="show_comfirmpassword ? 'text' : 'password'" dense outlined class="input_form icons_formularios my-0 py-0" hint="Confirme su nueva clave" :error-messages="errors.password">
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col sm="4" md="3" lg="3" xl="3" class="my-0 py-0 px-0">
                                    <v-subheader class="sub_header_form float-right mx-0 px-0">Rol:</v-subheader>
                                </v-col>
                                <v-col sm="8" md="9" lg="9" xl="9" class="my-0 py-0">
                                    <v-select v-model="form.role_id" label="Seleccione el rol del usuario" :items="ContenedorRoles"
                                    item-text="name" item-value="id" dense outlined append-icon="fas fa-user-tag" class="input_form icons_formularios input_form_select my-0 py-0"
                                    :error-messages="errors.role_id" hint="Por ejemplo, administrador" clearable>
                                    </v-select>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-form>
                </v-card-text>

                <v-card-actions class="mt-0 pt-0 py-3">
                    <v-btn color="primary" v-show="!editionmode" block :disabled="in_submit" @click.prevent="addUser">
                        <v-icon left>mdi-content-save</v-icon>
                        Agregar usuario
                    </v-btn>

                    <v-btn color="primary" v-show="editionmode" block :disabled="in_submit" @click.prevent="updateUser">
                        <v-icon left>mdi-content-save</v-icon>
                        Actualizar usuario
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
                people_id: '',
                name: '',
                lastname: '',
                type_document: '',
                number_document: '',
                direction: '',
                phone: '',
                email: '',
                username: '',
                password: '',
                password_confirmation: '',
                role_id: '',
                user_id: '',
            },

            documentos: [
                {item: 'DNI', text: 'DNI'},
                {item: 'C.I', text: 'C.I'},
                {item: 'RUC', text: 'RUC'}
            ],

            headers: [
                {text: 'Usuario', value: 'fullname'},
                {text: 'N# de documento', value: 'number_document'},
                {text: 'Teléfono', value: 'phone'},
                {text: 'Correo', value: 'email'},
                {text: 'Usuario', value: 'username'},
                {text: 'Rol', value: 'role_name'},
                {text: 'Acciones', value: 'id', sortable: false},
            ],

            ContenedorUsuarios: [],
            ContenedorRoles: [],
            show_password: false,
            show_comfirmpassword: false,
            editionmode: false,
            loading: false,
            dialog: false,
            in_submit: false,
            options: {},
            totalContenedorUsuarios: 0,
            errors: '',
            permissionsAuthUser: JSON.parse(localStorage.getItem('permissions'))
        }
    },

    watch: {
        options: {
            handler(){
                this.getListarUsuarios();
            },
            deep: true,
        },
    },

    methods: {
        getListarUsuarios(){
            let me = this;
            me.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = me.options
            axios.get('/api/users', {
                params: {
                    sortBy: sortBy[0],
                    sortDesc: sortDesc[0],
                    page: page,
                    itemsPerPage: itemsPerPage,
                    search: me.search,
                }
            }).then(response => {
                me.ContenedorUsuarios = response.data.users.data;
                me.totalContenedorUsuarios = response.data.users.total;
                me.ContenedorRoles = response.data.roles;
                me.loading = false;
            }).catch(error => {
                if(error.response.status == 401){
                    me.$router.push({name: 'login.index'});
                    location.reload();
                    localStorage.clear();
                }
            });
        },

        addUser(){
            let me = this;
            me.in_submit = true;
            axios.post('/api/users', {
                'name': me.form.name,
                'lastname': me.form.lastname,
                'type_document': me.form.type_document,
                'number_document': me.form.number_document,
                'direction': me.form.direction,
                'phone': me.form.phone,
                'email': me.form.email,
                'username': me.form.username,
                'password': me.form.password,
                'password_confirmation': me.form.password_confirmation,
                'role_id': me.form.role_id,
            }).then(() =>{
                me.in_submit = false;
                me.$notify({
                    title: 'Exitoso!',
                    message: 'Usuario registrado!',
                    type: 'success',
                    duration: 1500
                });
                Fire.$emit('afterCrearUsuario');
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
            console.log(item);
            let me = this;
            me.dialog = true;
            me.editionmode = true;
            me.form.people_id = item.people_id;
            me.form.name = item.name;
            me.form.lastname = item.lastname;
            me.form.type_document = item.type_document;
            me.form.number_document = item.number_document;
            me.form.direction = item.direction;
            me.form.phone = item.phone;
            me.form.email = item.email;
            me.form.username = item.username;
            me.form.role_id = item.role_id;
            me.form.user_id = item.user_id;
        },

        updateUser(){
            let me = this;
            me.in_submit = true;
            axios.put('/api/users/'+ me.form.user_id, {
                'name': me.form.name,
                'lastname': me.form.lastname,
                'type_document': me.form.type_document,
                'number_document': me.form.number_document,
                'direction': me.form.direction,
                'phone': me.form.phone,
                'email': me.form.email,
                'username': me.form.username,
                'password': me.form.password,
                'password_confirmation': me.form.password_confirmation,
                'role_id': me.form.role_id,
                'people_id': me.form.people_id,
            }).then(() => {
                me.in_submit = false;
                me.$notify({
                    title: 'Exitoso!',
                    message: 'Usuario actualizado!',
                    type: 'success',
                    duration: 1500
                });
                Fire.$emit('afterCrearUsuario');
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
            console.log(item);
            let me = this;
            me.$confirm('No podrás revertir esto!', '¿Está seguro?', {
                confirmButtonText: 'Si, Eliminar',
                cancelButtonText: 'Cancelar',
                type: 'warning',
                closeOnClickModal: false,
                closeOnPressEscape: true
            }).then(() => {
                axios.delete('/api/users/' + item.user_id, {
                    params: {
                        people_id: item.people_id
                    }
                }).then(()=>{
                    me.getListarUsuarios();
                    me.$notify({
                        title: 'Exitoso!',
                        message: 'Se ha eliminado el usuario',
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
            Fire.$emit('afterCrearUsuario');
        },

        resetPaginacion(){
            this.options.page = 1;
        },

        Alertar(){
            if(!this.form.type_document){
                this.errors = {type_document: ["Debe seleccionar primero el tipo de documento"], number_document: ["Debe seleccionar primero el tipo de documento"]}
            }else{
                this.errors = false;
            }
        }
    },

    created(){
        Fire.$on('afterCrearUsuario', () => {
            let me = this;
            me.getListarUsuarios();
            me.form.people_id = null;
            me.form.name = null;
            me.form.lastname = null;
            me.form.type_document = null;
            me.form.number_document = null;
            me.form.direction = null;
            me.form.phone = null;
            me.form.email = null;
            me.form.username = null;
            me.form.password = null;
            me.form.password_confirmation = null;
            me.form.role_id = null;
            me.form.user_id = null;
            me.errors = false;
            me.dialog = false;
        });
    }

}
</script>
