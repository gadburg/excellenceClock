<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
//formulario vacio
const form = useForm({
  ip: '',
  fecha: '',
  rango: '',
  ubicacion: '',
  total: '',
});

//funcion asincrona para obtener los datos de entrada del dia
const startTimer = async () => {
  try {
    const ipResponse = await fetch('https://api64.ipify.org?format=json');
    const ipData = await ipResponse.json();
    form.ip = ipData.ip;
    form.fecha = getCurrentDate();
    form.rango = getCurrentTime();

    const position = await getCurrentPosition();
    const ubicacion = await getCityAndRegion(position.coords.latitude, position.coords.longitude);
    form.ubicacion = ubicacion;

    form.total = "00:00:00";
    var boton = document.getElementById("fichar");
    boton.click();

  } catch (error) {
    console.error('Error al iniciar el temporizador:', error.message);
  }
};

startTimer();

//acciones al enviar formulario
const submitForm = () => {
    form.post(route('registros.store'), {
        onFinish: () => form.reset('rango', 'total'),
    });
};

//funcion para obtener la ubicacion, si no se puede obtener asigna unas coordenadas por defecto
const getCurrentPosition = () => {
  return new Promise((resolve, reject) => {
    navigator.geolocation.getCurrentPosition(
      (position) => resolve(position),
      (error) => {
        resolve({
          coords: {
            latitude: 0.0,
            longitude: 0.0
          },
          timestamp: Date.now()
        });
      }
    );
  });
};

//funcion para obtener el dia actual de madrid
const getCurrentDate = () => {
  const currentDate = new Date();
  const opciones = { timeZone: 'Europe/Madrid' };
  const fechaMadrid = currentDate.toLocaleDateString('es-ES', opciones);
  return fechaMadrid.split('/').reverse().join('-');
};

//funcion para obtener la hora actual de madrid
const getCurrentTime = () => {
  const currentDate = new Date();
  const opciones = { timeZone: 'Europe/Madrid', hour12: false };
  const horaMadrid = currentDate.toLocaleTimeString('es-ES', opciones);
  return horaMadrid.split(' ')[0];
};

//funcion asicrona que recibe los parametros de latitud y longuitud para retornar el nombre de las coordenadas, retorna la ciudad y la comunidad
const getCityAndRegion = async (latitude, longitude) => {
  try {
    //utiliza una API de geolocalizacion inversa para obtener la ciudad y la comunidad segun las coordenadas
    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`);
    const data = await response.json();
    return `${data.address.city}, ${data.address.state}`;
  } catch (error) {
    console.error('Error al obtener la ubicación:', error.message);
    return 'Ubicación no disponible';
  }
};
</script>

<template>
  <AppLayout title="Registros">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registros
            </h2>
        </template>
    <form @submit.prevent="submitForm" class="space-y-4">
     
       
        <input type="hidden" v-model="form.ip" id="ip" required>
        <input type="hidden" v-model="form.fecha" id="fecha" required>
        <input type="hidden" v-model="form.rango" id="rango" required>
        <input type="hidden" v-model="form.ubicacion" id="ubicacion" required>
        <input type="hidden" v-model="form.total" id="total" required>

      <button type="submit" id="fichar" style="display:none;" >Guardar</button>
    </form>
    </AppLayout>
</template>
