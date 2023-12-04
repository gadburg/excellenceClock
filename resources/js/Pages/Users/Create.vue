<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password:'default',
    id_empresa: '',
});

const empresas = ref([]);
const emailExists = ref(false);

const submitForm = async () => {
    //comprobamos el formulario
    await limpiarYComprobarEmail();

    //si la verificacion del mail es correcta enviamos el formulario
    if (!emailExists.value) {
        form.post(route('users.store'), {
            onFinish: () => form.reset('name', 'email'),
        });
    } else {
        form.email='';
        mostrarAlerta('error', 'El mail ya esta en uso');
    }
};

const mostrarAlerta = (tipo, mensaje) => {
    return Swal.fire({
        icon: tipo,
        title: mensaje,
        showConfirmButton: false,
        timer: 1500,
    });
};

const limpiarYComprobarEmail = async () => {
    //limpiamos el mail
    form.email = form.email.trim();

    //validamos
    if (!form.email || !/\S+@\S+\.\S+/.test(form.email)) {
        form.email='';
        mostrarAlerta('error', 'El mail no es valido');
        return;
    }

    //comprobamos si existe en la db
    await comprobarEmail();
};

//funcion asincrona para comprobar el mail en la db
const comprobarEmail = async () => {
    try {
        const response = await fetch(`/email-existe/${form.email}`);
        const data = await response.json();
        emailExists.value = data.exists;
    } catch (error) {
        console.error('Error al verificar el email:', error);
    }
};

//funcion asincrona para obtener las empresas de la db y pintarlas en el select
const obtenerEmpresas = async () => {
    try {
        const response = await fetch('/obtener-empresas');
        const data = await response.json();
        empresas.value = data;

    } catch (error) {
        console.error('Error al obtener las empresas:', error);
    }
};

//al montar
onMounted(() => {
    obtenerEmpresas();
});
</script>

<template>
    <AppLayout title="Crear Usuario">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Usuario
            </h2>
        </template>
        <div
            class="max-w-2xl mx-auto mt-10 bg-white p-5 rounded-xl shadow-xl animate__animated animate__bounceInLeft animate__delay-1s">
            <h1 class="text-2xl font-semibold mb-6">Crear Usuario</h1>

            <form @submit.prevent="submitForm" class="space-y-4">
                <div class="flex flex-col">
                    <label for="nombre" class="mb-1">Nombre del usuario:</label>
                    <input v-model="form.name" type="text" id="nombre" class="p-2 border rounded" required>
                </div>
                <div class="flex flex-col">
                    <label for="id_empresa">Empresa:</label>
                    <select v-model="form.id_empresa" id="id_empresa" required
                        class="w-full mb-1 rounded-md border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="" selected disabled>Selecciona la empresa</option>
                        <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">{{
                            empresa.nombre }}</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label for="cont" class="mb-1">Email:</label>
                    <input v-model="form.email" @blur="limpiarYComprobarEmail" type="text" id="email" class="p-2 border rounded" required>

                </div>
                <div class="flex flex-col">
                    <label for="cont" class="mb-1">Contraseña:</label>
                    <input v-model="form.password" type="text" id="password" class="p-2 border rounded" required>
                </div>
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 animate__animated animate__bounceInRight animate__delay-1s">Guardar</button>
            </form>
        </div>
    </AppLayout>
</template>
