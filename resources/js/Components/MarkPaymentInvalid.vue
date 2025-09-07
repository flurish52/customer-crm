<template>
    <div class="fixed inset-0 flex items-center justify-center z-50">
        <!-- Overlay -->
        <div       @click.self="$emit('cancel')"
                   class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Modal -->
        <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md p-6 z-10">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Mark Payment Invalid</h2>
            <p class="text-sm text-gray-600 mb-6">
                If you proceed, this payment will be marked as invalid and assumed to have been refunded to the client.
            </p>

            <div class="flex justify-end space-x-3">
                <button
                    @click="$emit('cancel')"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
                >
                    Cancel
                </button>
                <button
                    @click="markInvalid"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
                >
                    Continue
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import {router} from "@inertiajs/vue3";

const props = defineProps({
    payment: Object,
})
const markInvalid =()=>{
    axios.patch(`/mark_payment/invalid/${props.payment.id}`)
        .then(res=>{
            if(res.status === 200){
                router.visit(window.location.href, { preserveScroll: true})
            }
        }).catch(err=>{
            alert('Action failed, please try again')
    })
}
</script>

<style scoped>
</style>
