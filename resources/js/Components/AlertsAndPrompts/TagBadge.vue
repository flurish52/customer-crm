<template>
    <div
        class="absolute right-0"
        :class="positionClass"
    >
    <span
        class=" text-xs p-0.5"
        :class="colorClass"
    >
      {{ label }}
    </span>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    label: {
        type: String,
        required: true
    },
    status: {
        type: String,
        required: true
    },
    position: {
        type: String,
        default: 'top-right'
    }
})

const colorClass = computed(() => {
    switch (props.status) {
        case 'new':
            return 'text-white bg-primary/50  rounded-bl-lg'
        case 'contacted':
            return 'bg-yellow-500 rounded-bl-lg'
        case 'follow_up':
            return 'bg-orange-500 rounded-bl-lg'
        case 'converted':
            return 'bg-green-600 rounded-bl-lg'
        case 'lost':
            return 'bg-red-600 rounded-bl-lg'
        default:
            return 'bg-gray-500 rounded-bl-lg'
    }
})

const positionClass = computed(() => {
    return props.position;
    // === 'top-left'
    //     ? 'top-0 left-0'
    //     : 'top-0 right-0'
})
</script>
