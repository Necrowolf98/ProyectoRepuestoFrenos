<template>
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 3.0.4
        </div>
        <strong>Copyright &copy; {{date.getFullYear()}} <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
    </footer>
</template>

<script>
export default {
    data() {
        return {
            date: new Date()
        }
    },
}
</script>

<style>
</style>
