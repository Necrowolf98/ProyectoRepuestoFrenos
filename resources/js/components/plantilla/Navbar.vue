<template>
        <nav class="main-header navbar navbar-expand navbar-warning navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav pl-0">

                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Inicio</a>
                </li>
            </ul>


            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="dropdown li_logout">
                    <a href="#" class="a_logout d-flex pt-1" data-toggle="dropdown">
                        <v-avatar color="rgb(240, 233, 233)" size="33">
                            <a class="rgb(139, 93, 93)" style="text-decoration: none; color: #252457;" v-text="usuario.people.name.charAt(0)+usuario.people.lastname.charAt(0)"></a>
                        </v-avatar>
                        <!-- <img class="hover_photo img-size-27 img-circle mr-3" src="/storage/{{ Auth::user()->avatar }}"> -->
                    </a>

                    <div class="dropdown-menu menu_logout dropdown-menu-sm dropdown-menu-right">
                        <div class="text-center py-1">
                            <v-avatar color="rgb(240, 233, 233)" size="37">
                                <span class="rgb(139, 93, 93)" v-text="usuario.people.name.charAt(0)+usuario.people.lastname.charAt(0)"></span>
                            </v-avatar>
                        </div>
                        <p class="text-muted text-center my-0 p_logout"><strong>{{ usuario.people.fullname }}</strong></p>
                        <p class="text-muted text-center my-0 py-1 p_logout"><strong>Rol: </strong>{{ usuario.roles[0].name }}</p>
                        <p class="text-center text-muted mx-3 mb-0 p_logout">{{ usuario.people.email }}</p>

                        <div class="text-center mt-2">
                            <a class="text-muted">
                                <button class="btn text-muted boton_cerrar_sesion" @click.prevent="logout">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Cerrar sesión
                                </button>
                            </a>
                        </div>
                    </div>
                </li>



                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>

        </nav>
</template>

<script>
    export default {
        props: ['ruta', 'usuario'],

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
