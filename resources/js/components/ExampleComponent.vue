<template>
    <v-app>
        <v-alert dense type="success">
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Deserunt eum vel sunt numquam iste illo totam sequi impedit distinctio cupiditate consequuntur beatae quam voluptates, tenetur ab atque maiores fugit ad!
        </v-alert>
        <v-btn color="blue">sdfasdf</v-btn>
                <div class="mt-5 pt-5">
              <el-button icon="el-icon-search" circle></el-button>
  <el-button type="primary" icon="el-icon-edit" circle></el-button>
  <el-button type="success" icon="el-icon-check" circle></el-button>
  <el-button type="info" icon="el-icon-message" circle></el-button>
  <el-button type="warning" icon="el-icon-star-off" circle></el-button>
  <el-button type="danger" icon="el-icon-delete" circle></el-button>
        </div>
    </v-app>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
