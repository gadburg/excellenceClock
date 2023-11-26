<script>
export default{
    name:'Usuarios'
}

</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia'

defineProps({
    usuarios:{
        type:Object,
        required:true,
    }
})

const deleteUsuario = id => {
    if (confirm('¿Estás Seguro?')){
        Inertia.delete(route('usuarios.destroy', id))
    }
}

</script>

<template>
    <AppLayout title="Usuarios">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Usuarios
            </h2>
        </template>
        <div class="pl-2 max-w-4xl mx-auto flex justify-between items-center mt-3 rounded-md bg-white">
            <p class="text-lg font-bold py-1">Listado de Usuarios</p>
            <Link :href="route('empresas.create')"
                class="px-2 py-0 mr-2 bg-purple-500 hover:bg-purple-400 text-white font-bold border-b-4 border-purple-700 hover:border-purple-500 rounded">
            Crear Usuario
            </Link>
        </div>
        <div class="max-w-2xl mx-auto mt-3 bg-white overflow-x-auto rounded-md shadow-md">
            <table class="min-w-full">
                <thead class="bg-gray-600 text-white">
                    <tr>
                        <th class="py-2  pl-4">Id</th>
                        <th class="py-2">Nombre</th>
                        <th class="py-2">Email</th>
                        <th class="py-2">Empresa</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(usuario, index) in usuarios" :key="usuario.id"
                        :class="{ 'bg-gray-100': index % 2 === 0, 'bg-gray-200': index % 2 !== 0 }">
                        <td class="py-2  pl-4 text-center">{{ usuario.id_usuario }}</td>
                        <td class="py-2 text-center">{{ usuario.nombre }}</td>
                        <td class="py-2 text-center">{{ usuario.email }}</td>
                        <td class="py-2 text-center">{{ usuario.id_empresa }}</td>
                        <td class="py-2 text-center space-x-2">
                            <!--editar-->
                            <Link :href="route('usuarios.edit', usuario.id_usuario)"
                                class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-1 px-2 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                            Editar
                            </Link>
                            <!--borrar-->
                            <Link @click="deleteEmpresa(usuario.id_usuario)" :href="route('usuarios.index')" class="bg-red-500 hover:bg-red-400 text-white font-bold py-1 px-2 border-b-4 border-red-700 hover:border-red-500 rounded">
                            Borrar
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>