<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';

</script>

<template>
    <AppLayout title="Registro">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registro Horario
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome :userId="$page.props.auth.user.id" />
                   
                </div>
            </div>
        </div>
    </AppLayout>
</template>
