<template>
    <v-app id="app" style="background: #F4F6F9;">
        <v-card outlined>
            <v-card-title class="py-0 my-0" style="border-bottom: 0.1px solid #B0BEC5 ;">
                <v-col>
                    <span class="headline">Usuario: {{ people.fullname }}</span>
                    <router-link :to="{name: 'users.show', params: { id: $attrs.id } }">
                        <v-btn absolute right color="primary">
                            <v-icon class="mr-2">mdi-arrow-left</v-icon>
                            Regresar
                        </v-btn>
                    </router-link>
                </v-col>
            </v-card-title>

            <v-card-text>
                <form>
                    <v-row class="py-3">
                        <v-col cols="12" sm="12" md="6" lg="6" xl="6">
                            <v-card outlined>
                                <v-card-title class="blue lighten-1 py-1">
                                    <span class="subtitle-1 text-white ml-auto mr-auto">Lista de permisos por roles de usuario</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-data-table
                                        :headers="headers"
                                        :items="arrRoles"
                                        :hide-default-footer="true"
                                        disable-pagination
                                        show-expand
                                        no-data-text="Lo sentimos, no hay nada para mostrar aquí">

                                        <template v-slot:expanded-item="{ item }">
                                            <td colspan="2" class="py-3">
                                                <th>Nombre del permiso</th>
                                                <hr>
                                                <div class="d-flex flex-column">
                                                    <span v-for="(item1, index) in item.permissions" :key="index">
                                                        {{ item1.name }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td colspan="2" class="py-3">
                                                <th>Descripción del permiso</th>
                                                <hr>
                                                <div class="d-flex flex-column">
                                                    <span v-for="(item1, index) in item.permissions" :key="index">
                                                        {{ item1.description }}
                                                    </span>
                                                </div>
                                            </td>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                        </v-col>

                        <v-col cols="12" sm="12" md="6" lg="6" xl="6">
                            <v-card outlined>
                                <v-card-title class="blue lighten-1 py-1">
                                    <span class="subtitle-1 text-white ml-auto mr-auto">Listado de permisos</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-simple-table height="400px">
                                        <thead>
                                            <tr>
                                                <th>Nombre del permiso</th>
                                                <th>Descripción</th>
                                                <th>Fecha de creación</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr v-for="(item, index) in ContenedorPermisos" :key="index">
                                                <td>
                                                    <v-checkbox color="info"
                                                    :label="item.name"
                                                    :value="item"
                                                    v-model="form.permissions"
                                                    :error-messages="errors.permissions"
                                                    :value-comparator="comparatorRolesChecked"
                                                    ></v-checkbox>
                                                </td>
                                                <td v-text="item.description"></td>
                                                <td v-text="item.created_at"></td>
                                            </tr>
                                        </tbody>
                                    </v-simple-table>
                                </v-card-text>

                                <v-card-actions class="grey lighten-5 py-0 my-0">
                                    <v-col cols="6" class="p-0 m-0">
                                        <v-btn color="blue lighten-1" block small class="text-white" @click.prevent="updatePermissionsUser" :disabled="in_submit">
                                            <v-icon class="mr-1">mdi-content-save</v-icon>
                                            Actualizar
                                        </v-btn>
                                    </v-col>

                                    <v-col cols="6">
                                        <v-btn color="grey lighten-1" block small class="text-white" @click.prevent="cleanFormulario">
                                            <v-icon class="mr-1">mdi-alert</v-icon>
                                            Limpiar
                                        </v-btn>
                                    </v-col>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                    </v-row>
                </form>
            </v-card-text>
        </v-card>
    </v-app>
</template>

<script>
export default {
    data(){
        return {
            form: {
                id: this.$attrs.id,
                permissions: [],
            },
            people: [],
            arrRoles: [],
            ContenedorPermisos: [],
            errors: '',
            in_submit: false,

            headers: [
                {text: 'Nombre del rol', value: 'name', sortable: false},
                {text: 'Descripción del rol', value: 'description', sortable: false},
            ],

        }
    },
    mounted(){
        this.getPermissionsUser();
    },
    methods: {
        getPermissionsUser(){
            let me = this;
            axios.get('/api/users/'+ me.$attrs.id)
            .then(response => {
                me.people = response.data.user.people;
                me.arrRoles = response.data.user.roles;
                me.ContenedorPermisos = response.data.permissions;
                me.form.permissions = response.data.user.permissions;
            }).catch(error =>{
                if(error.response.status == 401){
                    me.$router.push({name: 'login.index'});
                    location.reload();
                    localStorage.clear();
                }
            });
        },

        updatePermissionsUser(){
            let me = this;
            me.in_submit = true;
            axios.put('/api/users/updatepermissionsbyuser/'+me.$attrs.id, {
                'permissions': me.form.permissions,
            }).then( () =>{
                me.in_submit = false;
                me.$notify({
                    title: 'Exitoso!',
                    message: 'Se ha actualizado los permisos del usuario.',
                    type: 'success',
                    duration: 2000
                });
                me.getPermisosByName();
                me.$router.push('/users');
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

        getPermisosByName(){
            axios.get('/api/authpermissions')
            .then(response => {
                localStorage.setItem('permissions', JSON.stringify(response.data.permisos));
                EventBus.$emit('notificarChangePermissions', response.data.permisos);
            }).catch(error =>{
                if(error.response.status == 401){
                    me.$router.push({name: 'login.index'});
                    location.reload();
                    localStorage.clear();
                }
            })
        },
        comparatorRolesChecked(a, b) {
            return a.id == b.id;
        },

        cleanFormulario(){
            this.form.permissions = [];
            this.errors = false;
        }
    }
}
</script>
