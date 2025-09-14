<template>
    <div v-if="show"
         @click.self="close"
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
            <div class="flex justify-between items-center p-6 border-b border-teal-100 bg-teal-50 rounded-t-xl">
                <h2 class="text-xl font-semibold text-teal-800">We Value Your Feedback</h2>
                <button @click="close" class="text-teal-600 hover:text-teal-800 text-lg font-bold">×</button>
            </div>

            <div class="overflow-y-auto p-6">
                <form @submit.prevent="submitFeedback" class="space-y-6">
                    <div v-if="error || message"
                         class="p-2 mb-2 rounded border  text-sm">
                        <p v-if="error" class="border-red-400 bg-red-50 text-red-700">{{ error }}</p>
                        <p v-if="message" class="border-teal-400 bg-teal-50 text-teal-700">{{ message }}</p>
                    </div>
                    <!-- Features you use most -->
                    <div>
                        <label class="block text-sm font-medium text-teal-700 mb-2">Features you use most:</label>
                        <div class="flex flex-wrap gap-3">
                            <label v-for="feature in features" :key="feature"
                                   class="inline-flex items-center px-3 py-2 rounded-full text-sm"
                                   :class="form.features.includes(feature)
                                           ? 'bg-teal-100 text-teal-800 border border-teal-300'
                                           : 'bg-gray-100 text-gray-700 border border-gray-200'">
                                <input type="checkbox" v-model="form.features" :value="feature" class="sr-only">
                                {{ feature }}
                            </label>
                        </div>
                    </div>

                    <!-- Modules used -->
                    <div>
                        <label class="block text-sm font-medium text-teal-700 mb-2">What your business needs
                            most:</label>
                        <div class="flex flex-wrap gap-3">
                            <label v-for="module in modules" :key="module"
                                   class="inline-flex items-center px-3 py-2 rounded-full text-sm"
                                   :class="form.modules.includes(module)
                                           ? 'bg-teal-100 text-teal-800 border border-teal-300'
                                           : 'bg-gray-100 text-gray-700 border border-gray-200'">
                                <input type="checkbox" v-model="form.modules" :value="module" class="sr-only">
                                {{ module }}
                            </label>
                        </div>
                    </div>

                    <!-- Frequency of use -->
                    <div>
                        <label class="block text-sm font-medium text-teal-700 mb-2">Frequency of use:</label>
                        <select v-model="form.frequency"
                                class="w-full border border-teal-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option disabled value="">Select frequency</option>
                            <option>Daily</option>
                            <option>Weekly</option>
                            <option>Monthly</option>
                            <option>Rarely</option>
                        </select>
                    </div>

                    <!-- Usability -->
                    <div>
                        <label class="block text-sm font-medium text-teal-700 mb-2">Ease of navigation:</label>
                        <select v-model="form.navigation"
                                class="w-full border border-teal-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option disabled value="">Select rating</option>
                            <option value="1">1 - Difficult</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5 - Very Easy</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-teal-700 mb-2">Speed and responsiveness:</label>
                        <select v-model="form.speed"
                                class="w-full border border-teal-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option disabled value="">Select rating</option>
                            <option value="1">1 - Very Slow</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5 - Very Fast</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-teal-700 mb-2">Any confusing steps?</label>
                        <textarea v-model="form.confusing" rows="2"
                                  class="w-full border border-teal-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500"></textarea>
                    </div>

                    <!-- Missing features -->
                    <div>
                        <label class="block text-sm font-medium text-teal-700 mb-2">Missing features / desired
                            reports:</label>
                        <textarea v-model="form.missingFeatures" rows="2"
                                  class="w-full border border-teal-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                  placeholder="What you though or want the app to have."></textarea>
                    </div>

                    <!-- Overall satisfaction -->
                    <div>
                        <label class="block text-sm font-medium text-teal-700 mb-2">Overall satisfaction:</label>
                        <div class="flex space-x-2">
                            <button v-for="n in 5" :key="n"
                                    type="button"
                                    @click="form.satisfaction = n"
                                    class="w-10 h-10 rounded-full flex items-center justify-center border"
                                    :class="form.satisfaction >= n
                                            ? 'bg-teal-500 text-white border-teal-600'
                                            : 'bg-white text-gray-400 border-teal-200 hover:bg-teal-50'">
                                {{ n }}
                            </button>
                        </div>
                    </div>

                    <!-- Likelihood to recommend -->
                    <div>
                        <label class="block text-sm font-medium text-teal-700 mb-2">Likelihood to recommend
                            (1-5):</label>
                        <div class="flex space-x-2">
                            <button v-for="n in 5" :key="'rec'+n"
                                    type="button"
                                    @click="form.recommend = n"
                                    class="w-10 h-10 rounded-full flex items-center justify-center border"
                                    :class="form.recommend >= n
                                            ? 'bg-teal-500 text-white border-teal-600'
                                            : 'bg-white text-gray-400 border-teal-200 hover:bg-teal-50'">
                                {{ n }}
                            </button>
                        </div>
                    </div>

                    <!-- Suggestions / improvements -->
                    <div>
                        <label class="block text-sm font-medium text-teal-700 mb-2">Open suggestions / pain
                            points:</label>
                        <textarea v-model="form.suggestions" rows="3"
                                  class="w-full border border-teal-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500"></textarea>
                    </div>

                    <!-- Business type -->
                    <div>
                        <label class="block text-sm font-medium text-teal-700 mb-2">Your business type:</label>
                        <select v-model="form.businessType"
                                class="w-full border border-teal-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option disabled value="">Select type</option>
                            <option>Artisan</option>
                            <option>Freelancer</option>
                            <option>Small Service Business</option>
                        </select>
                    </div>

                    <!-- Team size -->
                    <div>
                        <label class="block text-sm font-medium text-teal-700 mb-2">Your team size:</label>
                        <select v-model="form.teamSize"
                                class="w-full border border-teal-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option disabled value="">Select size</option>
                            <option>Solo</option>
                            <option>2-5</option>
                            <option>6-10</option>
                            <option>10+</option>
                        </select>
                    </div>
                    <div v-if="error || message"
                         class="p-2 mb-2 rounded border  text-sm">
                        <p v-if="error" class="border-red-400 bg-red-50 text-red-700">{{ error }}</p>
                        <p v-if="message" class="border-teal-400 bg-teal-50 text-teal-700">{{ message }}</p>
                    </div>


                    <!-- Submit button -->
                    <div class="pt-4 flex justify-end">
                        <button type="submit"
                                class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200">
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue'
import axios from 'axios'

defineProps({show: Boolean})
const emit = defineEmits(['close'])
const features = ['Client Management', 'Jobs/Projects', 'Invoices/Payments']
const modules = ['Client Management', 'Jobs/Projects', 'Invoices/Payments', 'Products/Services', 'Apprenticeship Management']
const form = ref({
    modules: [],
    features: [],
    frequency: '',
    navigation: '',
    speed: '',
    confusing: '',
    missingFeatures: '',
    satisfaction: null,
    recommend: null,
    suggestions: '',
    businessType: '',
    teamSize: ''
})
let error = ref('')
let message = ref('')


function close() {
    emit('close')
}

async function submitFeedback() {
    try {
        await axios.post('/feedback', form.value)
            .then(res => {
                message.value = res.data.message
                error.value = ''
            })
        setTimeout(() => {
            message.value = ''
            close()
            form.value = {
                modules: [],
                features: [],
                frequency: '',
                navigation: '',
                speed: '',
                confusing: '',
                missingFeatures: '',
                satisfaction: null,
                recommend: null,
                suggestions: '',
                businessType: '',
                teamSize: ''
            }
        }, 3000)
    } catch (err) {
        error.value = err.response.data.message || 'error submitting form';
        message.value = ''
    }
}
</script>
