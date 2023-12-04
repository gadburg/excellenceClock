<script>
export default {
    name: 'Registros',
    methods: {
        inicio() {

            this.$inertia.visit(route('registros.create'));

        },
        detener(id) {
                     
            this.$inertia.visit(route('registros.edit', id));
        },
    },
}

</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { defineProps, onMounted, ref, watch } from 'vue';


//propiedades del componente
const { registros } = defineProps(['registros']);

const tiempo = ref('00:00:00');

const registroUsadoId = ref(null);
const registroUsadoTiempo = ref(null);

var intervalId;
// Obtener el registro con la fecha de hoy
const obtenerRegistroHoy = () => {
    const fechaHoy = new Date().toISOString().split('T')[0];
    const tiempoDefecto = '00:00:00';
    //const detener = document.getElementById('detener');
    //const iniciar = document.getElementById('iniciar');
    //buscamos en los registros el que tenga la fecha de hoy
    const registroHoy = registros.find(registro => registro.fecha === fechaHoy);
    const registroTotal = registros.find(registro => registro.total === tiempoDefecto);
    // Si se encuentra el registro, puedes acceder a su id con registroHoy.id
    if (registroHoy) {
        registroUsadoId.value = registroHoy.id;
        //dividimos el campo rango con "-" si existe
        const partesRango = registroHoy.rango.split('-');
        if (partesRango.length > 1) {
            //calculamos la diferencia de tiempo entre los dos registros
            const tiempoAnterior = partesRango[0];
            const tiempoActual = partesRango[1];

            const segundos1 = obtenerSegundosDesdeMedianoche(tiempoAnterior);
            const segundos2 = obtenerSegundosDesdeMedianoche(tiempoActual);

            const diferenciaEnSegundos = segundos2 - segundos1;

            const horas = Math.floor(diferenciaEnSegundos / 3600);
            const minutos = Math.floor((diferenciaEnSegundos % 3600) / 60);
            const segundos = diferenciaEnSegundos % 60;

            tiempo.value = `${formatearNumero(horas)}:${formatearNumero(minutos)}:${formatearNumero(segundos)}`;
            
        } else {
            //si no hay guion "-", comparamos con la hora actual y mostramos la diferencia desde ese punto
            registroUsadoTiempo.value = registroTotal;
            const tiempoAnterior = partesRango[0];
            const tiempoActual = obtenerTiempoActual();
            //const diferencia = tiempoActual - tiempoAnterior;

            const segundos1 = obtenerSegundosDesdeMedianoche(tiempoAnterior);
            const segundos2 = obtenerSegundosDesdeMedianoche(tiempoActual);

            const diferenciaEnSegundos = segundos2 - segundos1;
            const horas = Math.floor(diferenciaEnSegundos / 3600);
            const minutos = Math.floor((diferenciaEnSegundos % 3600) / 60);
            const segundos = diferenciaEnSegundos % 60;
            
            //detener.disabled = false;

            tiempo.value = `${formatearNumero(horas)}:${formatearNumero(minutos)}:${formatearNumero(segundos)}`;
        }
    }
    
}

const obtenerTiempoActual = () => {
  const opciones = { hour: '2-digit', minute: '2-digit', second: '2-digit', timeZone: 'Europe/Madrid' };
  const tiempoActual = new Date().toLocaleTimeString('es-ES', opciones);
  return tiempoActual;
};

function obtenerSegundosDesdeMedianoche(hora) {
    const [hh, mm, ss] = hora.split(':');
    return parseInt(hh, 10) * 3600 + parseInt(mm, 10) * 60 + parseInt(ss, 10);
}

const formatearNumero = (numero) => {
    return numero < 10 ? `0${numero}` : numero;
};

onMounted(() => {
    obtenerRegistroHoy();
    intervalId = setInterval(() => {
        obtenerRegistroHoy();
    }, 1000);
});

</script>

<template>
    <AppLayout title="Registros">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registros
            </h2>
        </template>
        <div class="flex flex-col items-center mt-10">
            <!-- Botones a los lados -->
            <div class="flex space-x-8">
                <button id="iniciar"
                    class="bg-green-500 hover:bg-green-400 text-white font-bold border-b-4 border-green-700 hover:border-green-500 rounded-lg py-3 px-6 disabled:cursor-not-allowed"
                    :class="{ 'disabled:cursor-not-allowed': registroUsadoId !== null }"
                    :disabled="registroUsadoId !== null"
                    @click="inicio">
                    Iniciar
                </button>
                <button id="detener"
                    class="bg-yellow-500 hover:bg-yellow-400 text-white font-bold border-b-4 border-yellow-700 hover:border-yellow-500 rounded-lg py-3 px-6 disabled:cursor-not-allowed"
                    :class="{ 'disabled:cursor-not-allowed': !registroUsadoId }"
                    :disabled="registroUsadoId === null || registroUsadoTiempo === null"
                    @click="() => detener(registroUsadoId)" >
                    Detener
                </button>
            </div>

            <!-- Contador de reloj en el centro y más arriba -->
            <div class="mt-4 text-4xl font-bold bg-black rounded-xl text-white animate__animated animate__zoomIn animate__delay-1s p-1 shadow-lg" id="contadorReloj">
                {{ tiempo }}
            </div>
        </div>
        <div class="grid place-items-center">
            <div class="max-w-4xl mx-auto flex justify-between items-center mt-3 rounded-xl bg-white shadow-lg ">
                <p class="text-lg font-bold py-1 pl-5 pr-5">Registros</p>
            </div>
        </div>
        <div class="max-w-2xl mx-auto mt-3 bg-white shadow-xl rounded-xl">
            <table class="min-w-full shadow-xl rounded-xl">
                <thead class="bg-gray-600 text-white rounded-xl">
                    <tr class=" rounded-xl">
                        <th>IP</th>
                        <th>Fecha</th>
                        <th>Horas</th>
                        <th>Ubicación</th>
                        <th>Horas Totales</th>
                    </tr>
                </thead>
                <tbody  class="rounded-xl">
                    <tr v-for="(registro, index) in registros" :key="registro.id"
                        :class="{ 'bg-gray-100': index % 2 === 0, 'bg-gray-200': index % 2 !== 0 }">
                        <td class=" p-1 text-center">{{ registro.ip }}</td>
                        <td class=" p-1 text-center">{{ registro.fecha }}</td>
                        <td class=" p-1 text-center">{{ registro.rango }}</td>
                        <td class=" p-1 text-center">{{ registro.ubicacion }}</td>
                        <td class=" p-1 text-center">{{ registro.total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>