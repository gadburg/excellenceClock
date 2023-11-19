<template>
    <form @submit.prevent="login">
      <input v-model="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Contraseña" />
      <button type="submit">Iniciar sesión</button>
    </form>
  </template>
  
  <script>
  export default {
    data() {
      return {
        email: '',
        password: '',
      };
    },
    methods: {
      login() {
        // Aquí puedes utilizar axios u otra biblioteca para enviar la solicitud a la API de Laravel
        // Ejemplo con axios:
        axios.post('/login', { email: this.email, password: this.password })
          .then(response => {
            // Manejar la respuesta exitosa (redirección, almacenamiento de token, etc.)
          })
          .catch(error => {
            // Manejar el error de autenticación
          });
      },
    },
  };
  </script>