<script>
export default {
    name: 'UserEdit'
}
</script>
<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps, onMounted } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

const form = useForm({
    name: props.user.name,
    email: props.user.email,
})

const submitForm = () => {
    
    form.put(route('users.update', {id: props.user.id} ), {
        onSuccess: () => {
            console.log(props.empresa);
        },
    });
}

</script>

<template>
    <AppLayout title="nuevo">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar User {{ user }}
            </h2>
        </template>
        <div class="max-w-2xl mx-auto mt-10 bg-white p-5 rounded-xl shadow-xl animate__animated animate__bounceInLeft animate__delay-1s">
            <h1 class="text-2xl font-semibold mb-6">Editar Usuarios</h1>
            <form @submit.prevent="submitForm" class="space-y-4">
                <div class="flex flex-col">
                    <label for="nombre" class="mb-1">Nombre del usuario:</label>
                    <input v-model="form.name" type="text" id="nombre" class="p-2 border rounded" required>
                </div>

                <div class="flex flex-col">
                    <label for="responsable" class="mb-1">Email:</label>
                    <input v-model="form.responsable" type="text" id="responsable" class="p-2 border rounded" required>
                </div>

                <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-white font-bold border-b-4 border-amber-700 hover:border-amber-500 rounded-lg py-2 px-4 animate__animated animate__bounceInRight animate__delay-1s">Actualizar</button>
            </form>
        </div>
    </AppLayout>
</template>