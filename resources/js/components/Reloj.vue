<!--<template>
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

    <button class="mt-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" @click="stopTimer"
      :disabled="!isRunning">
      Detener
    </button>
  </div>
</template>
  
<script setup>
import { ref, onMounted } from 'vue';


const isRunning = ref(false);
const isPaused = ref(false);
const startTime = ref(null);
let accumulatedTime = ref(0);
const formattedTime = ref('00:00:00');
let pauseTime = ref(null);
let resumeTime = ref(null);
let stopTime = ref(null);

onMounted(() => {
  // Actualiza el tiempo cada segundo cuando el temporizador está en funcionamiento
  setInterval(updateTime, 1000);
});

const startTimer = async () => {
  isRunning.value = true;
  startTime.value = Date.now();

  // Obtener la dirección IP
  const ipResponse = await fetch('https://api64.ipify.org?format=json');
  const ipData = await ipResponse.json();
  const ipAddress = ipData.ip;

  try {
    // Obtener la ubicación (latitud y longitud) usando la API de geolocalización del navegador
    const position = await getCurrentPosition();
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;

    // Haz algo con la latitud y longitud (por ejemplo, mostrar en consola)
    console.log(`Ubicación: Latitud ${latitude}, Longitud ${longitude}`);
  } catch (error) {
    console.error('Error al obtener la ubicación:', error.message);
  }

  // Obtener la hora y fecha actual
  const currentDate = new Date();
  const currentDateTime = currentDate.toISOString();

  // Haz algo con la dirección IP, ubicación y la fecha actual (por ejemplo, mostrar en consola)
  console.log(`Dirección IP: ${ipAddress}`);
  console.log(`Fecha y hora actual: ${currentDateTime}`);
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


</script>-->

<!--
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

    <button class="mt-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" @click="stopTimer"
      :disabled="!isRunning">
      Detener
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const isRunning = ref(false);
const isPaused = ref(false);
const startTime = ref(null);
let accumulatedTime = ref(0);
const formattedTime = ref('00:00:00');
let pauseTime = ref(null);
let resumeTime = ref(null);
let stopTime = ref(null);

onMounted(() => {
  // Actualiza el tiempo cada segundo cuando el temporizador está en funcionamiento
  setInterval(updateTime, 1000);
});

const startTimer = async () => {
  isRunning.value = true;
  startTime.value = Date.now();

  // Obtener la dirección IP
  const ipResponse = await fetch('https://api64.ipify.org?format=json');
  const ipData = await ipResponse.json();
  const ipAddress = ipData.ip;

  try {
    // Obtener la ubicación (latitud y longitud) usando la API de geolocalización del navegador
    const position = await getCurrentPosition();
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;

    // Obtener la hora y fecha actual
    const currentDate = new Date();
    const currentDateTime = currentDate.toISOString();

    // Realizar la solicitud para registrar la hora de entrada
    await registerTime({
      usuario_id: $page.props.auth.user.id, // Reemplaza con el ID del usuario en uso
      fecha: currentDateTime,
      hora_entrada: currentDateTime,
      longitud: longitude,
      latitud: latitude,
      ip: ipAddress,
    });

    // Haz algo con la latitud, longitud, dirección IP, y la fecha y hora actual
    console.log(`Ubicación: Latitud ${latitude}, Longitud ${longitude}`);
    console.log(`Dirección IP: ${ipAddress}`);
    console.log(`Fecha y hora de entrada: ${currentDateTime}`);
  } catch (error) {
    console.error('Error al obtener la ubicación o registrar la hora de entrada:', error.message);
  }
};

const registerTime = async (data) => {
  try {
    // Realizar la solicitud al servidor para almacenar la hora de entrada/salida
    const response = await fetch('/api/registros', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        usuario_id: 1,
        fecha: '2023-01-01',
        hora_entrada: '12:00:00',
        longitud: 0.0,
        latitud: 0.0,
        ip: '192.168.0.1',
        hora_salida: '13:00:00',
      }),
    });

    const responseData = await response.json();
    console.log('Registro horario almacenado:', responseData);
  } catch (error) {
    console.error('Error al almacenar el registro horario:', error.message);
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

  // Obtén la hora de salida al detener el temporizador
  const stopDateTime = new Date().toISOString();

  try {
    // Realiza una solicitud al servidor para almacenar la hora de salida
    await registerTime({
      usuario_id: 1, // Reemplaza con el ID del usuario en uso
      fecha: stopDateTime,
      hora_salida: stopDateTime,
    });

    // Haz algo con la fecha y hora de salida
    console.log(`Fecha y hora de salida: ${stopDateTime}`);
  } catch (error) {
    console.error('Error al registrar la hora de salida:', error.message);
  }

  // Restablece el temporizador
  resetTimer();
};

const resetTimer = () => {
  formattedTime.value = '00:00:00';
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
</script>
----------------------------------------------------------------------------------------------------------------------->

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

<script setup>
import { ref, onMounted } from 'vue';
//import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
//const { post } = usePage();
console.log(router);
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
const props = defineProps(['userId']);
var usuario =  props.userId;


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
    id_usuario: usuario,
    fecha: fecha,
    hora_entrada: horaEntrada,
    hora_salida: horaSalida,
    longitud: longitud,
    latitud: latitud,
    ip: ipUser,
  };


  sendDataToBackend(datos);
  console.log(datos);

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
    await post('/dashboard', data);
    //await this.$inertia.post('/registros', data);
    console.log('Datos enviados exitosamente.');
  } catch (error) {
    console.error('Error al enviar datos:', error.message);
  }
};

</script>
<!--
<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
  id_usuario: '',
  fecha: '',
  hora_entrada: '',
  hora_salida: '',
  longitud: '',
  latitud: '',
  ip: '',
});

const submit = async () => {
  form.post(route('reloj'), {
        onFinish: () => form.reset('ip', 'latitud'),
    });
};
</script>
<template>
  <div>
    <h1>Crear Registro</h1>

    <form @submit.prevent="submit">
      <label for="id_usuario">ID Usuario:</label>
      <input v-model="form.id_usuario" required>

      <label for="fecha">Fecha:</label>
      <input type="date" v-model="form.fecha" required>

      <label for="hora_entrada">Hora Entrada:</label>
      <input type="time" v-model="form.hora_entrada" required>

      <label for="hora_salida">Hora Salida:</label>
      <input type="time" v-model="form.hora_salida" required>

      <label for="longitud">Longitud:</label>
      <input type="number" v-model="form.longitud" required>

      <label for="latitud">Latitud:</label>
      <input type="number" v-model="form.latitud" required>

      <label for="ip">IP:</label>
      <input v-model="form.ip" required>

      <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Registrar
                </PrimaryButton>
    </form>
  </div>
</template>

-->