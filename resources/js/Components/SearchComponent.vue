<template>
    <div class="relative">
        <input
            type="text"
            v-model="searchQuery"
            @keyup="sendSearchQuery(searchQuery)"
            placeholder="Search customers..."
            class="pl-10 pr-4 py-2 border border-tertiary-dark rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-primary-light"
        >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5"
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>
</template>

<script setup>
import {ref} from "vue";

let searchQuery = ref()
let emit = defineEmits(['sendSearchQuery'])

const sendSearchQuery = () =>{
    emit('sendSearchQuery', {payload: searchQuery.value})
}

</script>

<style scoped>

</style>
