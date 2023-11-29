<script>
export default {
    name: 'RegistroEdit'
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps, onMounted } from 'vue';

const props = defineProps({
    registro: {
        type: Object,
        required: true
    }
})

const form = useForm({
    rango: props.registro.rango,
    total: props.registro.total,
});

const submitForm = () => {
    form.put(route('registros.update', props.registro ), {
        onSuccess: () => {
            
        },
    });
}

const startTimer = async () => {
    try {

        const tiempoAnterior = form.rango;
        const tiempoActual = new Date().toISOString().split('T')[1].split('.')[0];;
        //const diferencia = tiempoActual - tiempoAnterior;
        form.rango=form.rango+" - "+tiempoActual;
        const segundos1 = obtenerSegundosDesdeMedianoche(tiempoAnterior);
        const segundos2 = obtenerSegundosDesdeMedianoche(tiempoActual);

        const diferenciaEnSegundos = segundos2 - segundos1;
        const horas = Math.floor(diferenciaEnSegundos / 3600);
        const minutos = Math.floor((diferenciaEnSegundos % 3600) / 60);
        const segundos = diferenciaEnSegundos % 60;


        form.total = `${formatearNumero(horas)}:${formatearNumero(minutos)}:${formatearNumero(segundos)}`;
        var boton = document.getElementById("guardar");
        boton.click();

    } catch (error) {
        console.error('Error:', error.message);
    }
};

function obtenerSegundosDesdeMedianoche(hora) {
    const [hh, mm, ss] = hora.split(':');
    return parseInt(hh, 10) * 3600 + parseInt(mm, 10) * 60 + parseInt(ss, 10);
}

const formatearNumero = (numero) => {
    return numero < 10 ? `0${numero}` : numero;
};

onMounted(() => {
    startTimer();
});
</script>

<template>
    <AppLayout title="Registros">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registros
            </h2>
        </template>
        <form @submit.prevent="submitForm" class="space-y-4">

            <input type="hidden" v-model="form.rango" id="rango" required>
            <input type="hidden" v-model="form.total" id="total" required>

            <button type="submit" id="guardar" style="display: none;">Guardar</button>
        </form>
    </AppLayout>
</template>
