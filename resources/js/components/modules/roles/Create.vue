<template>
    <div>
        <v-card outlined>
            <v-card-title class="py-0 my-0" style="border-bottom: 0.1px solid #B0BEC5 ;">
                <v-col>
                    <span class="headline">Creación de roles</span>
                    <router-link to="/roles">
                        <v-btn absolute right color="primary">
                            <v-icon class="mr-2">mdi-arrow-left</v-icon>
                            Regresar
                        </v-btn>
                    </router-link>
                </v-col>
            </v-card-title>

            <v-card-text>
                <v-form ref="form">
                    <v-row class="py-3">
                        <v-col cols="12" sm="12" md="5" lg="5" xl="5">
                            <v-card outlined>
                                <v-card-title class="blue lighten-1 py-1">
                                    <span class="subtitle-1 text-white ml-auto mr-auto">Formulario registro de rol</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-row>
                                        <v-col class="mb-0 pb-0" cols="12" sm="5" md="12" lg="12" xl="12">
                                            <v-row class="pt-3">
                                                <v-col cols="3" sm="4" md="3" lg="3" xl="3">
                                                    <v-subheader class="sub_header_form float-md-right float-lg-right float-xl-right text-muted mx-0 px-0">Nombre:</v-subheader>
                                                </v-col>
                                                <v-col cols="9" sm="8" md="9" lg="9" xl="9" class="pb-0 mb-0">
                                                    <v-text-field v-model="form.name" label="Escriba el nombre del rol" append-icon="fas fa-unlock-alt" dense outlined class="input_form icons_formularios" hint="Por ejemplo Listar usuarios"  :error-messages="errors.name">
                                                    </v-text-field>
                                                </v-col>
                                            </v-row>
                                        </v-col>

                                        <v-col class="mb-0 pb-0" cols="12" sm="7" md="12" lg="12" xl="12">
                                            <v-row class="pt-3">
                                                <v-col cols="3" sm="3" md="3" lg="3" xl="3">
                                                    <v-subheader class="sub_header_form float-md-right float-lg-right float-xl-right text-muted mx-0 px-0">Descripción</v-subheader>
                                                </v-col>
                                                <v-col cols="9" sm="9" md="9" lg="9" xl="9">
                                                    <v-text-field v-model="form.description" label="Escriba la descripción del rol a crear" append-icon="fas fa-unlock-alt" dense outlined class="input_form icons_formularios" hint="Por ejemplo, rol encargado de controlar las ventas" :error-messages="errors.description">
                                                    </v-text-field>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                    </v-row>

                                </v-card-text>

                                <v-card-actions class="grey lighten-5 py-0 my-0">
                                    <v-col cols="6" class="p-0 m-0">
                                        <v-btn color="blue lighten-1" block small class="text-white" @click.prevent="addRole" :disabled="in_submit">
                                            <v-icon class="mr-1">mdi-content-save</v-icon>
                                            Registrar
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

                        <v-col cols="12" sm="12" md="7" lg="7" xl="7">
                            <v-card outlined>
                                <v-card-title class="blue lighten-1 py-1">
                                    <span class="subtitle-1 text-white ml-auto mr-auto">Listado de permisos</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-simple-table height="500px">
                                        <thead>
                                            <tr>
                                                <th>Nombre del rol</th>
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
                                                    @change="checkPermisosByChecked"
                                                    ></v-checkbox>
                                                </td>
                                                <td v-text="item.description"></td>
                                                <td v-text="item.created_at"></td>
                                            </tr>
                                        </tbody>
                                    </v-simple-table>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                id: '',
                name: '',
                description: '',
                permissions: [],
            },
            errors: '',
            ContenedorPermisos: [],
            in_submit: false,
        }
    },
    mounted(){
        this.getPermisos();
    },
    methods:{
        getPermisos(){
            let me = this;
            axios.get('/api/roles/create')
            .then(response =>{
                me.ContenedorPermisos = response.data;
            }).catch(error =>{
                if(error.response.status == 401){
                    me.$router.push({name: 'login.index'});
                    location.reload();
                    localStorage.clear();
                }
            });
        },

        addRole(){
            let me = this;
            me.in_submit = true;
            axios.post('/api/roles', {
                'name': me.form.name,
                'description': me.form.description,
                'permissions': me.form.permissions,
            }).then( () =>{
                me.in_submit = false;
                me.$notify({
                    title: 'Exitoso!',
                    message: 'Se ha creado el rol',
                    type: 'success',
                    duration: 2000
                });
                me.$router.push('/roles');
            }).catch(error =>{
                me.in_submit = false;
                me.errors = error.response.data.errors;
                if(me.errors.permissions){
                    me.$confirm('Debe seleccionar al menos un permiso!','Oops.', {
                        confirmButtonText: 'Ok',
                        showCancelButton: false,
                        type: 'error',
                        closeOnClickModal: false,
                        closeOnPressEscape: true
                    });
                }
                if(error.response.status == 401){
                    me.$router.push({name: 'login.index'});
                    location.reload();
                    localStorage.clear();
                }
            });
        },

        checkPermisosByChecked(){
            if(this.errors.permissions){
                if(this.form.permissions){
                    this.errors.permissions = '';
                }
            }
        },

        cleanFormulario(){
            this.$refs.form.reset();
            this.errors = false;
        }
    },
}
</script>
