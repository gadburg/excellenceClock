<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  nombre: '',
  responsable: '',
});

const submitForm = () => {

    form.post(route('empresas.store'), {
        onFinish: () => form.reset('nombre', 'responsable'),
    });
};

</script>

<template>
    <AppLayout title="Crear empresa">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Empresa
            </h2>
        </template>
  <div class="max-w-2xl mx-auto mt-10 bg-white p-5 rounded-xl shadow-xl animate__animated animate__bounceInLeft animate__delay-1s">
    <h1 class="text-2xl font-semibold mb-6">Crear Empresa</h1>
    <form @submit.prevent="submitForm" class="space-y-4">
      <div class="flex flex-col">
        <label for="nombre" class="mb-1">Nombre de la Empresa:</label>
        <input v-model="form.nombre" type="text" id="nombre" class="p-2 border rounded" required>
      </div>

      <div class="flex flex-col">
        <label for="responsable" class="mb-1">Responsable:</label>
        <input v-model="form.responsable" type="text" id="responsable" class="p-2 border rounded" required>
      </div>

      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 animate__animated animate__bounceInRight animate__delay-1s">Guardar</button>
    </form>
  </div>
  </AppLayout>
</template>
