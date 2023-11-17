<template>
    <footer class="app-footer bg-light py-3">
        <div class="container">
            <div class="text-center">
                <span>&copy;</span>
                <span>{{ appName }}</span>
                <span class="fecha">{{ anio }}</span>
            </div>
        </div>
    </footer>
</template>
  
<script>
export default {
    data() {
        return {
            appName: " ExcellenceClock ",
            anio: this.obtenerAnio(),
        };
    },
    methods: {
        obtenerAnio() {
            const now = new Date();
            return now.getFullYear().toString();
        },
    },
};
</script>
  
<style scoped>
.fecha {
    font-style: italic;
}
</style>
  