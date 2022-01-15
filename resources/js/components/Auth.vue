<template>
    <v-app>
        <div class="login-page">
            <router-view></router-view>
        </div>
    </v-app>
</template>
<script>
export default {


    mounted() {
        this.comprobarLogin();
    },

    methods: {
        comprobarLogin(){
            axios.get('/api/comprobarLogin').then(response =>{
                if(response.data.code == '401'){
                    localStorage.clear();
                    if(this.$route.path == '/dashboard'){
                        location.reload()
                    }
                }else{
                    console.log(response.data);
                }
            }).catch(error => {
                console.log(error);
            });
        }
    }
}
</script>
