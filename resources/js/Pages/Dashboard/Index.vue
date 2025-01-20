<script setup>

import Layout from '../Layout/App.vue'
const props = defineProps({
    data: Array
})

</script>

<template>

    <Layout>
        <div class="container mx-auto py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="(stat, index) in props.data" :key="index"
                    class="relative p-2 bg-white rounded-lg shadow-lg border hover:cursor-pointer hover:bg-gray-300">
                    <h2 class="text-lg font-semibold mb-1">{{ stat.title }}</h2>
                    <p class="text-2xl font-bold">{{ stat.value ?? "..." }}</p>
                </div>
            </div>
        </div>
    </Layout>
</template>
