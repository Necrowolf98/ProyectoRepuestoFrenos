<template>
    <v-app style="overflow: auto !important;">
        <Navbar :ruta="ruta" :usuario="usuario"></Navbar>

        <Sidebar :ruta="ruta" :usuario="usuario" :permissionsAuthUser="permissionsAuthUser"></Sidebar>

        <div class="content-wrapper pt-3 pb-5">
            <section class="content">
                    <v-main>
                        <v-container fluid>
                            <transition name="slide-fade" mode="out-in">
                                <router-view></router-view>
                            </transition>
                        </v-container>
                    </v-main>
            </section>
        </div>

        <Footer></Footer>

        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>

    </v-app>
</template>

<script>
    import Navbar from './plantilla/Navbar'
    import Sidebar from './plantilla/Sidebar'
    import Footer from './plantilla/Footer'
    export default {
        props: ['ruta', 'usuario'],
        components: {Navbar, Sidebar, Footer},

        data(){
            return {
                permissionsAuthUser: []
            }
        },

        mounted(){
            this.permissionsAuthUser = JSON.parse(localStorage.getItem('permissions'));
            EventBus.$on('notificarChangePermissions', data =>{
                this.permissionsAuthUser = data;
            });
        }
    }
</script>
