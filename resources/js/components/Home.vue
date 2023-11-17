<!-- resources/js/Home.vue -->
<template>
    <div>
        <header-component></header-component>
        <main class="container mt-5">
            <section>
                <h2>Bienvenido a nuestra Landing Page</h2>
                <p>Descubre todo lo que ofrecemos...</p>
            </section>
        </main>
        <footer-component></footer-component>
    </div>
</template>

<script>
import HeaderComponent from './Header.vue';
import FooterComponent from './Footer.vue';

export default {
    components: {
        'header-component': HeaderComponent,
        'footer-component': FooterComponent,
    },
    // Lógica de la página principal si es necesario
}
</script>

<style scoped>
/* Estilos específicos de la página principal */
</style>