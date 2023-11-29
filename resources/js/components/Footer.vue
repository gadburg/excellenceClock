<template>
    <footer class="bg-gray-100 py-4 mt-4">
        <div class="container mx-auto">
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
  