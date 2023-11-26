<script>
export default{
    name:'EmpresasIndex'
}

</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia'

defineProps({
    empresas:{
        type:Object,
        required:true,
    }
})

const deleteEmpresa = id => {
    if (confirm('¿Estás Seguro?')){
        Inertia.delete(route('empresas.destroy', id))
    }
}

</script>

<template>
    <AppLayout title="Registro">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Empresas
            </h2>
        </template>
        <div class="pl-2 max-w-4xl mx-auto flex justify-between items-center mt-3 rounded-md bg-white">
            <p class="text-lg font-bold py-1">Listado de empresas</p>
            <Link :href="route('empresas.create')"
                class="bg-green-500 text-white px-2 py-0 mr-2 rounded hover:bg-green-700">
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
                    <tr v-for="(empresa, index) in empresas" :key="empresa.id"
                        :class="{ 'bg-gray-100': index % 2 === 0, 'bg-gray-200': index % 2 !== 0 }">
                        <td class="py-2  pl-4 text-center">{{ empresa.id_empresas }}</td>
                        <td class="py-2 text-center">{{ empresa.nombre }}</td>
                        <td class="py-2 text-center">{{ empresa.responsable }}</td>
                        <td class="py-2 text-center space-x-2">
                            <!--editar-->
                            <Link :href="route('empresas.edit', empresa.id_empresas)"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700">
                            Editar
                            </Link>
                            <!--borrar-->
                            <Link @click="deleteEmpresa(empresa.id_empresas)" :href="route('empresas.index')" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">
                            Borrar
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>