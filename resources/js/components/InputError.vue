<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-red-600">
            <!--{{ message }}-->
           Los datos no son correctos.
        </p>
    </div>
</template>
