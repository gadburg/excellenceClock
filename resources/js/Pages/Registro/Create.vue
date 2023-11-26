<script>
export default {
    name: 'Registros'
}

</script>

<script setup>
//import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
//const { post } = usePage();
import { Inertia } from '@inertiajs/inertia';
import { defineProps, onMounted, ref } from 'vue';
const isRunning = ref(false);
const isPaused = ref(false);
const startTime = ref(null);
let accumulatedTime = ref(0);
const formattedTime = ref('00:00:00');
let pauseTime = ref(null);
let resumeTime = ref(null);
let stopTime = ref(null);

var fecha;
var horaEntrada;
var horaSalida;
var latitud;
var longitud;
var ipUser;


onMounted(() => {
  // Actualiza el tiempo cada segundo cuando el temporizador está en funcionamiento
  setInterval(updateTime, 1000);
});

const startTimer = async () => {
  isRunning.value = true;
  startTime.value = Date.now();

  try {
    // Lógica de inicio de sesión (puedes adaptar esto según tus necesidades)

    // Obtener la dirección IP
    var ipResponse = await fetch('https://api64.ipify.org?format=json');
    var ipData = await ipResponse.json();
    ipUser = ipData.ip;

    // Obtener la ubicación (latitud y longitud) usando la API de geolocalización del navegador
    var position = await getCurrentPosition();
    latitud = position.coords.latitude;
    longitud = position.coords.longitude;

    // Obtener la hora y fecha actual
    var currentDate = new Date();

    // Separar la fecha y la hora
    fecha = currentDate.toISOString().split('T')[0];
    horaEntrada = currentDate.toISOString().split('T')[1].split('.')[0];
    //usuario=props.userId;

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

const stopTimer = () => {
  isRunning.value = false;
  isPaused.value = false;
  accumulatedTime.value = 0;

  var currentDate = new Date();

    // Separar la fecha y la hora
    fecha = currentDate.toISOString().split('T')[0];
    horaSalida = currentDate.toISOString().split('T')[1].split('.')[0];

  var datos = {
    fecha: fecha,
    hora_entrada: horaEntrada,
    hora_salida: horaSalida,
    longitud: longitud,
    latitud: latitud,
    ip: ipUser,
  };

  console.log(datos);
  sendDataToBackend(datos);
  

};
const updateTime = () => {
  if (isRunning.value && !isPaused.value) {
    // Calcula la diferencia de tiempo y formatea el resultado
    const currentTime = Date.now();
    accumulatedTime.value = currentTime - startTime.value;
    const elapsedSeconds = Math.floor(accumulatedTime.value / 1000);

    // Formatea los segundos en formato HH:mm:ss
    const hours = Math.floor(elapsedSeconds / 3600);
    const minutes = Math.floor((elapsedSeconds % 3600) / 60);
    const seconds = elapsedSeconds % 60;

    // Agrega ceros a la izquierda si es necesario
    const formattedHours = String(hours).padStart(2, '0');
    const formattedMinutes = String(minutes).padStart(2, '0');
    const formattedSeconds = String(seconds).padStart(2, '0');

    // Actualiza el tiempo formateado
    formattedTime.value = `${formattedHours}:${formattedMinutes}:${formattedSeconds}`;
  }
};

const sendDataToBackend = async (data) => {
  try {
    // Utiliza el método post de Inertia para enviar datos al controlador
    //await post('/dashboard', data);
    Inertia.post('registros', data);
    console.log('Datos enviados exitosamente.');
  } catch (error) {
    console.error('Error al enviar datos:', error.message);
  }
};

</script>

<template>
    <div>
      <div class="mt-4 text-4xl font-bold">
        {{ formattedTime }}
      </div>
      <button class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="startTimer"
        :disabled="isRunning || isPaused">
        Iniciar
      </button>
  
      <button class="mt-4 bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
        @click="isPaused ? resumeTimer() : pauseTimer()" :disabled="!isRunning">
        {{ isPaused ? 'Reanudar' : 'Pausar' }}
      </button>
  
      <button type="submit" class="mt-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" @click="stopTimer"
        :disabled="!isRunning">
        Detener
      </button>
    </div>
  </template>
  