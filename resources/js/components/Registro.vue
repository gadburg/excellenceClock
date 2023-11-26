<template>
    <div>
      <div class="mt-4 text-4xl font-bold">
        {{ formattedTime }}
      </div>
  
      <form @submit.prevent="stopTimer" v-if="isRunning">
        <!-- Agrega campos del formulario según tus necesidades -->
        <input type="hidden" name="id_usuario" :value="usuario" />
        <input type="hidden" name="fecha" :value="fecha" />
        <input type="hidden" name="hora_entrada" :value="horaEntrada" />
        <input type="hidden" name="hora_salida" :value="horaSalida" />
        <input type="hidden" name="longitud" :value="longitud" />
        <input type="hidden" name="latitud" :value="latitud" />
        <input type="hidden" name="ip" :value="ipUser" />
  
        <button type="submit" class="mt-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
          Detener
        </button>
      </form>
  
      <div v-else>
        <button class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="startTimer">
          Iniciar
        </button>
  
        <button class="mt-4 bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
          @click="isPaused ? resumeTimer() : pauseTimer" :disabled="!isRunning">
          {{ isPaused ? 'Reanudar' : 'Pausar' }}
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { Head, Link, useForm } from '@inertiajs/vue3';
  
  const { data, post, processing } = useForm({
    id_usuario: null,
    fecha: null,
    hora_entrada: null,
    hora_salida: null,
    longitud: null,
    latitud: null,
    ip: null,
  });
  
  const isRunning = ref(false);
  const isPaused = ref(false);
  const startTime = ref(null);
  let accumulatedTime = ref(0);
  const formattedTime = ref('00:00:00');
  let pauseTime = ref(null);
  let resumeTime = ref(null);
  
  var fecha;
  var horaEntrada;
  var horaSalida;
  var latitud;
  var longitud;
  var ipUser;
  const props = defineProps(['userId']);
  var usuario = props.userId;
  
  onMounted(() => {
    setInterval(updateTime, 1000);
  });
  
  const startTimer = async () => {
    isRunning.value = true;
    startTime.value = Date.now();
  
    try {
      var ipResponse = await fetch('https://api64.ipify.org?format=json');
      var ipData = await ipResponse.json();
      ipUser = ipData.ip;
  
      var position = await getCurrentPosition();
      latitud = position.coords.latitude;
      longitud = position.coords.longitude;
  
      var currentDate = new Date();
      fecha = currentDate.toISOString().split('T')[0];
      horaEntrada = currentDate.toISOString().split('T')[1].split('.')[0];
    } catch (error) {
      latitud = 0.0;
      longitud = 0.0;
      console.error('Error al iniciar sesión o enviar datos al backend:', error.message);
    }
  };
  
  const getCurrentPosition = () => {
    return new Promise((resolve, reject) => {
      navigator.geolocation.getCurrentPosition(
        (position) => resolve(position),
        (error) => reject(error)
      );
    });
  };
  
  const pauseTimer = () => {
    isPaused.value = true;
    accumulatedTime.value = Date.now() - startTime.value;
  };
  
  const resumeTimer = () => {
    isPaused.value = false;
    startTime.value = Date.now() - accumulatedTime.value;
  };
  
  const stopTimer = async () => {
    isRunning.value = false;
    isPaused.value = false;
    accumulatedTime.value = 0;
  
    var currentDate = new Date();
    fecha = currentDate.toISOString().split('T')[0];
    horaSalida = currentDate.toISOString().split('T')[1].split('.')[0];
  
    // Asigna valores al formulario
    data.value = {
      id_usuario: usuario,
      fecha: fecha,
      hora_entrada: horaEntrada,
      hora_salida: horaSalida,
      longitud: longitud,
      latitud: latitud,
      ip: ipUser,
    };
  
    // Envia el formulario
    await post('/reloj');
    console.log('Datos enviados exitosamente.');
  };
  
  const updateTime = () => {
    if (isRunning.value && !isPaused.value) {
      const currentTime = Date.now();
      accumulatedTime.value = currentTime - startTime.value;
      const elapsedSeconds = Math.floor(accumulatedTime.value / 1000);
  
      const hours = Math.floor(elapsedSeconds / 3600);
      const minutes = Math.floor((elapsedSeconds % 3600) / 60);
      const seconds = elapsedSeconds % 60;
  
      const formattedHours = String(hours).padStart(2, '0');
      const formattedMinutes = String(minutes).padStart(2, '0');
      const formattedSeconds = String(seconds).padStart(2, '0');
  
      formattedTime.value = `${formattedHours}:${formattedMinutes}:${formattedSeconds}`;
    }
  };
  </script>