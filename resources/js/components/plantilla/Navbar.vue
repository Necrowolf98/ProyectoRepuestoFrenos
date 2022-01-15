<template>
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
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

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img :src="ruta + '/img/avatar.png'" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img :src="ruta + '/img/avatar.png'" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img :src="ruta + '/img/avatar.png'" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Nora Silvester
                                        <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
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
