<template>
    <header class="app-header py-1">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <img src="../../assets/logo.jpg" alt="Logo de la Empresa"
                            class="rounded-circle img-fluid mb-2 max-height-50" />
                    </a>
                    <span class="company-name" v-if="loggedIn">{{ companyName }}</span>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="#">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Iniciar Sesión</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Acerca de</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
</template>
  
<script>
export default {
    data() {
        return {
            loggedIn: true, // Cambia a true si el usuario está autenticado
            companyName: "Toshiba",
        };
    },
};
</script>
  
<style scoped>
.app-header {
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
}

.max-height-50 {
    max-height: 50px;
}

.company-name {
    font-weight: bold;
}

.nav-link {
    text-decoration: none;
    color: #333;
    padding: 10px 15px;
    border-radius: 0 0 10px 10px;
    transition: background-color 0.3s;
}

.nav-link:hover {
    background-color: #eee;
}
</style>
  