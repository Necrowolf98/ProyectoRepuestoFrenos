<template>
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Admin</b>LTE</a>
            </div>

            <div class="card-body">
                <p class="login-box-msg mb-0">Regístrese para iniciar su sesión</p>

                <form autocomplete="off">
                    <v-col class="my-0 py-0 px-0">
                        <v-text-field label="Ingrese su usuario" v-model="form.username" class="input_form icons_formularios" dense outlined append-icon="fas fa-user" @keyup.enter="login" :error-messages="errors.username"></v-text-field>
                    </v-col>

                    <v-col class="my-0 py-0 px-0">
                        <v-text-field :type="showPassword ? 'text' : 'password'" label="Ingrese su clave" v-model="form.password" class="input_form icons_formularios" dense outlined :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'" @click:append="showPassword = !showPassword" @keyup.enter="login" :error-messages="errors.username"></v-text-field>
                    </v-col>

                    <div class="row">
                        <div class="col-6">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">Recuérdame</label>
                            </div>
                        </div>

                        <div class="col-6">
                            <v-btn color="primary" block class="text-none text-white" :disabled="in_submit" @click.prevent="login">Iniciar sesión</v-btn>
                        </div>
                    </div>
                </form>

            <div class="social-auth-links text-center mt-2 mb-3">
                <v-btn color="primary" block class="text-none text-white"><i class="fab fa-facebook mr-2"></i> Iniciar sesión con Facebook</v-btn>
                <v-btn color="error" block class="text-none mt-2 text-white"><i class="fab fa-google-plus mr-2"></i> Iniciar sesión con Google+</v-btn>
            </div>

            <p class="mb-1">
                <router-link :to="'/recuperarpassword'">Olvidé mi contraseña</router-link>
            </p>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            form: {
                username: '',
                password: '',
                device_name: 'browser',
            },
            showPassword: false,
            errors: '',

            in_submit: false,
        }
    },

    methods: {
        login(){
            let me = this;
            me.in_submit = true,
            axios.post('/api/login', {
                'username': me.form.username,
                'password': me.form.password,
                'device_name': me.form.device_name
            }).then( response =>{
                me.in_submit = false;
                localStorage.setItem('token', response.data.token);
                localStorage.setItem('permissions', JSON.stringify(response.data.permissions));
                location.reload();
            }).catch( error =>{
                me.in_submit = false;
                me.errors = error.response.data.errors;
                localStorage.clear();
            });
        }
    }

}
</script>
