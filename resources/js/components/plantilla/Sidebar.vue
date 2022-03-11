<template>
    <aside class="main-sidebar sidebar-dark-warning elevation-4">
        <!-- Brand Logo -->
        <a href="#" class="brand-link">
            <img src="/img/Logoimpor.png" alt="Sistema Laravel y Vue" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">ImportBrands S.A</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <v-avatar color="rgb(240, 233, 233)" size="33">
                        <span class="rgb(139, 93, 93)" v-text="usuario.people.name.charAt(0)+usuario.people.lastname.charAt(0)"></span>
                    </v-avatar>
                    <!-- <img :src="ruta+'/img/user2-160x160.jpg'" class="img-circle elevation-2" alt="User Image"> -->
                </div>
                <div class="info" style="background: none !important">
                    <a href="#" class="d-block" v-text="usuario.people.fullname"></a>
                </div>
            </div>

            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image" style="background: none !important">
                    <a href="#" class="d-block" @click.prevent="logout">
                        <i class="fas fa-sign-out-alt ml-3 mt-2"></i>
                    </a>
                </div>

                <div class="info" style="background: none !important">
                    <a href="#" class="d-block" @click.prevent="logout">
                        Cerrar sesión
                    </a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">

                <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column ml-0 pl-0" data-widget="treeview" role="menu" data-accordion="false">

                    <li class="nav-item">
                        <template v-if="permissionsAuthUser.includes('dashboard.index')">
                            <router-link class="nav-link" to="/dashboard">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </router-link>
                        </template>
                    </li>


                    <li class="nav-item">
                        <template v-if="permissionsAuthUser.includes('repuesto.index')">
                            <router-link class="nav-link" to="/repuesto">
                                <i class="nav-icon fas fa-tools"></i>
                                <p>Repuestos</p>
                            </router-link>
                        </template>
                    </li>


                    <li class="nav-item">
                        <template v-if="permissionsAuthUser.includes('marca.index')">
                            <router-link class="nav-link" to="/marca">
                                <i class="nav-icon fas fa-car"></i>
                                <p>Marcas vehiculos</p>
                            </router-link>
                        </template>
                    </li>


                    <li class="nav-header">ADMINISTRACIÓN</li>

                    <li class="nav-item">
                        <template v-if="permissionsAuthUser.includes('users.index')">
                            <router-link class="nav-link" to="/users">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Usuarios</p>
                            </router-link>
                        </template>
                    </li>

                    <li class="nav-item">
                        <template v-if="permissionsAuthUser.includes('roles.index')">
                            <router-link class="nav-link" to="/roles">
                                <i class="nav-icon fas fa-unlock-alt"></i>
                                <p>Roles</p>
                            </router-link>
                        </template>
                    </li>

                    <li class="nav-item">
                        <template v-if="permissionsAuthUser.includes('permissions.index')">
                            <router-link class="nav-link" to="/permissions">
                                <i class="nav-icon fas fa-key"></i>
                                <p>Permisos</p>
                            </router-link>
                        </template>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</template>

<script>
    export default {
        props: ['ruta', 'usuario', 'permissionsAuthUser'],

        methods: {
            logout(){
                var url = '/api/logout';
                axios.post(url).then( () => {
                    localStorage.clear();
                    this.$router.push('/');
                    location.reload();
                })
            }
        },
    }
</script>
