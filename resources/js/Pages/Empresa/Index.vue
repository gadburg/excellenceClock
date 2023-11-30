<script>
export default {
    name: 'EmpresasIndex',
    
}

</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

defineProps({
    empresas: {
        type: Object,
        required: true,
    }
})
  
const deleteEmpresa = id => {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'No podrás revertir esto',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(route('empresas.destroy', id));
            
        }
    })
}

</script>

<template>
    <AppLayout title="Empresas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Empresas
            </h2>
        </template>
        <div
            class="pl-2 max-w-4xl mx-auto flex justify-between items-center mt-3 rounded-md bg-white animate__animated animate__rotateIn animate__rotateInUpRight animate__delay-1s">
            <p class="text-lg font-bold py-1">Listado de empresas</p>{{ empresas }}
            <Link :href="route('empresas.create')"
                class="px-2 py-0 mr-2 bg-purple-500 hover:bg-purple-400 text-white font-bold border-b-4 border-purple-700 hover:border-purple-500 rounded">
            Crear Empresa 
            </Link>
        </div>
        <div class="max-w-2xl mx-auto mt-3 bg-white overflow-x-auto rounded-md shadow-md">
            <table class="min-w-full">
                <thead class="bg-gray-600 text-white">
                    <tr>
                        <th class="py-2  pl-4">Id</th>
                        <th class="py-2">Nombre</th>
                        <th class="py-2">Responsable</th>
                        <th class="py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!--<tr v-for="(empresa, index) in empresas" :key="empresa.id"
                        :class="{ 'bg-gray-100': index % 2 === 0, 'bg-gray-200': index % 2 !== 0 }">-->
                        <tr v-for="empresa in empresas" :key="empresa.id_empresas">
                        <td class="py-2  pl-4 text-center">{{ empresa.id_empresas }}</td>
                        <td class="py-2 text-center">{{ empresa.nombre }}</td>
                        <td class="py-2 text-center">{{ empresa.responsable }}</td>
                        <td class="py-2 text-center space-x-2">
                            <!--editar-->
                            <button :href="route('empresas.edit', empresa.id_empresas)"
                                class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-1 px-2 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                            Editar
                            </button>
                            <!--borrar-->
                            <button @click="deleteEmpresa(empresa.id_empresas)" 
                                class="bg-red-500 hover:bg-red-400 text-white font-bold py-1 px-2 border-b-4 border-red-700 hover:border-red-500 rounded">
                            Borrar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>