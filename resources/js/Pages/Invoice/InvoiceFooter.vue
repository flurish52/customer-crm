<template>
<section class="mb-8 flex flex-wrap gap-3">
<button @click="downloadPDF" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    Download PDF
</button>

<button @click="shareAsImage" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m0 4h12v12H3V7z"/>
    </svg>
    Share as Image
</button>

<button @click="shareEmail" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m0 0H6a2 2 0 00-2 2v4a2 2 0 002 2h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2"/>
    </svg>
    Share via Email
</button>

<button @click="printInvoice" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V3h12v6M6 15h12v6H6v-6z"/>
    </svg>
    Print
</button>
</section>
</template>

<script setup>
const downloadPDF = () => alert("Download PDF placeholder");
const shareAsImage = () => alert("Share as Image placeholder");
const shareEmail = () => alert("Share via Email placeholder");
let emits = defineEmits(['print'])
const printInvoice = () => {
    emits('print', {invoiceContainer: 'invoiceContainer'})

};
</script>
