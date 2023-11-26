<script>
export default {
    name: 'Registros'
}

</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { defineProps, onMounted, ref } from 'vue';
import Reloj from '@/Pages/Registro/Create.vue';

//propiedades del componente
const { registros } = defineProps(['registros']);

//referencia reactiva para almacenar los datos con la ubicacion procesada
const registrosConCiudad = ref([]);

//funcion para obtener la ciudad a partir de las coordenadas
const obtenerCiudad = (latitud, longitud) => {
    return fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitud}&lon=${longitud}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                return data.address.state;
            } else {
                return 'Ciudad Desconocida';
            }
        })
        .catch(error => {
            console.error('Error al obtener la ciudad:', error.message);
            return 'Ciudad Desconocida';
        });
};

//funcion asincrona para obtener las ciudades de cada registro
const obtenerCiudadesParaRegistros = async () => {
    const registrosConCiudadActualizados = await Promise.all(
        registros.map(async (registro) => {
            const ciudad = await obtenerCiudad(registro.latitud, registro.longitud);
            return { ...registro, ciudad };
        })
    );
    //actualizamos la referencia reactiva con la ciudad
    registrosConCiudad.value = registrosConCiudadActualizados;
};

//al montar la pagina llamamos a la funcion para generar los datos de la ciudad
onMounted(() => {
    obtenerCiudadesParaRegistros();
});
</script>

<template>
    <AppLayout title="Registros">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registros
            </h2>
        </template>
        <Reloj :registros="registros" />
        <div class="grid place-items-center">
            <div class="max-w-4xl mx-auto flex justify-between items-center mt-3 rounded-md bg-white">
                <p class="text-lg font-bold py-1 pl-5 pr-5">Registros</p>
            </div>
        </div>
        <div class="max-w-2xl mx-auto mt-3 bg-white overflow-x-auto rounded-md shadow-md">
            <table class="min-w-full">
                <thead class="bg-gray-600 text-white">
                    <tr>
                        <th class="py-2">Fecha</th>
                        <th class="py-2">Hora Entrada</th>
                        <th class="py-2">Hora Salida</th>
                        <th class="py-2">Ubicación</th>
                        <th class="py-2 pr-1">IP</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(registro, index) in registrosConCiudad" :key="registro.id"
                        :class="{ 'bg-gray-100': index % 2 === 0, 'bg-gray-200': index % 2 !== 0 }">
                        <td class="py-2 text-center">{{ registro.fecha }}</td>
                        <td class="py-2 text-center">{{ registro.hora_entrada }}</td>
                        <td class="py-2 text-center">{{ registro.hora_salida }}</td>
                        <td class="py-2 text-center">{{ registro.ciudad }}</td>
                        <td class="py-2 text-center">{{ registro.ip }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>