<template>
        <div class="">
            <nav class="flex overflow-x-auto border-b border-gray-200 w-full">
                <div class="flex space-x-1">
                    <button
                        v-for="tab in tabs"
                        :key="tab.name"
                        class="relative px-4 py-2 w-full md:w-44 text-sm font-medium whitespace-nowrap flex items-center group"
                        :class="activeTab === tab.name
                ? 'text-gray-800 bg-tertiary border border-gray-300 border-b-white rounded-t-md'
                : 'text-tertiary hover:text-tertiary-dark rounded-t-md bg-primary-dark border border-transparent'"
                        @click="switchTab(tab.name)"
                    >
                        {{ tab.name }}
                    </button>
                </div>
            </nav>
        </div>
</template>
<script setup>
import {ref} from "vue";
const tabs = [
    { name: 'Jobs' },
    { name: 'Invoices' },
    { name: 'Activities' }
]

let activeTab = ref('Jobs')
let emits  = defineEmits(['switchTab'])

const switchTab=(tabName)=>{
    activeTab.value = tabName
    emits('switchTab', {payload: tabName})

}

</script>
