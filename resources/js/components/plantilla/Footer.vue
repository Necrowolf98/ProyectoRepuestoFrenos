<template>
    <footer class="main-footer">

        <strong>Copyright &copy; {{date.getFullYear()}} <a href="#">ImportBrands S.A</a>.</strong> Todos los derechos reservados.
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
