<script>
export default {
    name: 'UsersIndex',
    methods: {
        confirmarBorrado (id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Se borrará para siempre',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.visit(route('users.destroy', id), { method: 'delete' });

                }
            })
        }
    }
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia'

defineProps({
    users: Array,
})

</script>

<template>
    <AppLayout title="Usuarios">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Usuarios
            </h2>
        </template>
        <div class="pl-2 max-w-4xl mx-auto flex justify-between items-center mt-3 rounded-md bg-white animate__animated animate__rotateIn animate__rotateInUpRight animate__delay-1s">
            <p class="text-lg font-bold py-1">Listado de Usuarios</p>
            <Link :href="route('users.create')"
                class="px-2 py-0 mr-2 bg-purple-500 hover:bg-purple-400 text-white font-bold border-b-4 border-purple-700 hover:border-purple-500 rounded">
            Crear Usuario
            </Link>
        </div>
        <div class="max-w-2xl mx-auto mt-3 bg-white overflow-x-auto rounded-md shadow-md">
    <table class="min-w-full">
        <thead class="bg-gray-600 text-white">
            <tr>
                <th class="py-2 text-center sm:w-1/4" v-if="$page.props.user.permissions.includes('create empresa')">Nombre Empresa</th>
                <th class="py-2 text-center sm:w-1/4">Nombre</th>
                <th class="py-2 text-center sm:w-1/4">Email</th>
                <th class="py-2 text-center sm:w-1/4">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(user, index) in users" :key="user.id"
                :class="{ 'bg-gray-100': index % 2 === 0, 'bg-gray-200': index % 2 !== 0 }">
                <td class="py-2 text-center sm:w-1/4" v-if="$page.props.user.permissions.includes('create empresa')">
                    {{ user.empresa.nombre }}</td>
                <td class="py-2 text-center sm:w-1/4">{{ user.name }}</td>
                <td class="py-2 text-center sm:w-1/4">{{ user.email }}</td>
                <td class="py-2 text-center sm:w-1/4 space-x-2">
                    <div class="m-2 items-center sm:flex-row sm:items-center">
                        <Link @click="confirmarBorrado(user.id)" :href="route('users.index')"
                            class="flex-shrink-0 bg-red-500 hover:bg-red-400 text-white font-bold py-1 px-2 border-b-4 border-red-700 hover:border-red-500 rounded">
                        Borrar
                        </Link>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
    </AppLayout>
</template>