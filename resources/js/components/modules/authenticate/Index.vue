<template>
    <div class="login-box">
        <div class="card card-outline ">
            <div class="card-body text-center">
                <img src="/img/Imporbrands.png" class="avatar" alt="Avatar Image">
            </div>

            <div class="card-body pt-4 mt-4">
                <p class="login-box-msg mb-0">Ingresa sus datos para inciar sesión</p>

                <form autocomplete="off">
                    <v-col class="my-0 py-0 px-0">
                        <v-text-field label="Ingrese su usuario" v-model="form.username" class="input_form icons_formularios" dense outlined append-icon="fas fa-user" @keyup.enter="login" :error-messages="errors.username"></v-text-field>
                    </v-col>

                    <v-col class="my-0 py-0 px-0">
                        <v-text-field :type="showPassword ? 'text' : 'password'" label="Ingrese su clave" v-model="form.password" class="input_form icons_formularios" dense outlined :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'" @click:append="showPassword = !showPassword" @keyup.enter="login" :error-messages="errors.username"></v-text-field>
                    </v-col>

                    <v-btn color="amber" block  :disabled="in_submit" @click.prevent="login">Iniciar sesión</v-btn>
                </form>
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
