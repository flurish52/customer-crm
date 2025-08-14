<template>
    <section class="bg-gradient-to-b from-white to-tertiary-50 py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-primary-900 mb-3">
                    Pay Nothing. Get Everything.
                </h2>
                <p class="text-lg text-primary-600 max-w-2xl mx-auto">
                    Zelvra's free plan isn't a trial - it's <span class="font-semibold text-primary-800">fully-powered forever</span>.
                    Upgrade only if you need extras.
                </p>
            </div>

            <!-- Toggle -->
<!--            <div class="flex justify-center mb-10">-->
<!--                <div class="inline-flex bg-primary-100 p-1 rounded-lg">-->
<!--                    <button-->
<!--                        @click="billingCycle = 'monthly'"-->
<!--                        :class="{'bg-white shadow-sm text-primary-900': billingCycle === 'monthly'}"-->
<!--                        class="px-6 py-2 text-sm font-medium rounded-md transition-all"-->
<!--                    >-->
<!--                        Monthly-->
<!--                    </button>-->
<!--                    <button-->
<!--                        @click="billingCycle = 'annually'"-->
<!--                        :class="{'bg-white shadow-sm text-primary-900': billingCycle === 'annually'}"-->
<!--                        class="px-6 py-2 text-sm font-medium rounded-md transition-all"-->
<!--                    >-->
<!--                        Annually (1 month free)-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->

            <!-- Pricing Cards -->
<!--            <div class="grid md:grid-cols-3 gap-8">-->
                <!-- Free Tier (Strategic Position) -->
            <section class="bg-gradient-to-b from-tertiary-50 to-white py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-md mx-auto">
                    <div class="relative bg-white rounded-xl shadow-xl overflow-hidden border border-primary-100 hover:border-primary-200 transition-all duration-300 hover:shadow-primary-200/20">
                        <!-- Popular Badge (Optional) -->
                        <div class="absolute top-4 right-4 bg-primary-100 text-primary-800 text-xs font-bold px-3 py-1 rounded-full">
                            MOST POPULAR
                        </div>

                        <!-- Pricing Header -->
                        <div class="p-8 text-center border-b border-primary-50">
                            <h3 class="text-2xl font-extrabold text-primary-900">Free Forever Plan</h3>
                            <p class="text-primary-600 mt-2">Everything you need to manage clients</p>

                            <div class="mt-6">
                                <span class="text-5xl font-bold text-primary-900">$0</span>
                                <span class="text-lg text-primary-600">/month</span>
                                <p class="text-sm text-primary-500 mt-1">No credit card required</p>
                            </div>
                        </div>

                        <!-- Features List -->
                        <div class="p-8">
                            <ul class="space-y-4">
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <CheckCircleIcon class="h-5 w-5 text-green-500" />
                                    </div>
                                    <span class="ml-3 text-base text-primary-700 font-medium">Unlimited clients & projects</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <CheckCircleIcon class="h-5 w-5 text-green-500" />
                                    </div>
                                    <span class="ml-3 text-base text-primary-700 font-medium">Full job tracking system</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <CheckCircleIcon class="h-5 w-5 text-green-500" />
                                    </div>
                                    <span class="ml-3 text-base text-primary-700 font-medium">Basic reporting dashboard</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <CheckCircleIcon class="h-5 w-5 text-green-500" />
                                    </div>
                                    <span class="ml-3 text-base text-primary-700 font-medium">Client communication tools</span>
                                </li>
                            </ul>

                            <!-- CTA Button -->
                            <div class="mt-10">
                                <button class="w-full bg-primary-600 hover:bg-primary-700 text-white py-4 px-6 rounded-lg font-bold text-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                                    Start Managing Clients Now
                                </button>
                            </div>

                            <!-- Trust Badge -->
                            <div class="mt-6 flex items-center justify-center text-center">
                                <ShieldCheckIcon class="h-5 w-5 text-primary-500 mr-2" />
                                <span class="text-sm text-primary-600">No hidden fees</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

<!--                &lt;!&ndash; Popular Tier (Middle Position) &ndash;&gt;-->
<!--                <div class="relative rounded-xl border-2 border-primary-300 bg-white shadow-xl overflow-hidden">-->
<!--                    <div class="absolute top-0 right-0 bg-primary-600 text-white px-4 py-1 text-xs font-bold rotate-12 translate-x-2 -translate-y-2">-->
<!--                        MOST POPULAR-->
<!--                    </div>-->
<!--                    <div class="p-6">-->
<!--                        <h3 class="text-xl font-bold text-primary-900 mb-1">Professional</h3>-->
<!--                        <p class="text-primary-600 mb-4">For growing teams</p>-->

<!--                        <div class="mb-6">-->
<!--              <span class="text-4xl font-extrabold text-primary-900">-->
<!--                {{ billingCycle === 'monthly' ? '$15' : '$12' }}-->
<!--              </span>-->
<!--                            <span class="text-primary-600">/month</span>-->
<!--                            <span v-if="billingCycle === 'annually'" class="block text-sm text-green-600">$180 billed annually (save $36)</span>-->
<!--                        </div>-->

<!--                        <button class="w-full bg-gradient-to-r from-primary-500 to-tertiary-500 hover:opacity-90 text-white py-3 px-4 rounded-lg font-medium transition-all">-->
<!--                            Start Free Trial-->
<!--                        </button>-->
<!--                    </div>-->

<!--                    <div class="border-t border-primary-100 px-6 py-5">-->
<!--                        <ul class="space-y-3">-->
<!--                            <li class="flex items-center">-->
<!--                                <CheckCircleIcon class="h-5 w-5 text-green-500 mr-2" />-->
<!--                                <span><strong>Everything in Free</strong></span>-->
<!--                            </li>-->
<!--                            <li class="flex items-center">-->
<!--                                <CheckCircleIcon class="h-5 w-5 text-green-500 mr-2" />-->
<!--                                <span>Custom report branding</span>-->
<!--                            </li>-->
<!--                            <li class="flex items-center">-->
<!--                                <CheckCircleIcon class="h-5 w-5 text-green-500 mr-2" />-->
<!--                                <span>Team collaboration</span>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->

<!--                &lt;!&ndash; Enterprise Tier &ndash;&gt;-->
<!--                <div class="relative border border-gray-200 rounded-xl bg-white shadow-md overflow-hidden">-->
<!--                    <div class="p-6">-->
<!--                        <h3 class="text-xl font-bold text-primary-900 mb-1">Enterprise</h3>-->
<!--                        <p class="text-primary-600 mb-4">For agencies</p>-->

<!--                        <div class="mb-6">-->
<!--              <span class="text-4xl font-extrabold text-primary-900">-->
<!--                {{ billingCycle === 'monthly' ? '$45' : '$36' }}-->
<!--              </span>-->
<!--                            <span class="text-primary-600">/month</span>-->
<!--                            <span v-if="billingCycle === 'annually'" class="block text-sm text-green-600">$432 billed annually (save $108)</span>-->
<!--                        </div>-->

<!--                        <button class="w-full bg-white border-2 border-primary-600 text-primary-600 hover:bg-primary-50 py-3 px-4 rounded-lg font-medium transition-colors">-->
<!--                            Contact Sales-->
<!--                        </button>-->
<!--                    </div>-->

<!--                    <div class="border-t border-primary-100 px-6 py-5">-->
<!--                        <ul class="space-y-3">-->
<!--                            <li class="flex items-center">-->
<!--                                <CheckCircleIcon class="h-5 w-5 text-green-500 mr-2" />-->
<!--                                <span><strong>Everything in Pro</strong></span>-->
<!--                            </li>-->
<!--                            <li class="flex items-center">-->
<!--                                <CheckCircleIcon class="h-5 w-5 text-green-500 mr-2" />-->
<!--                                <span>Priority support</span>-->
<!--                            </li>-->
<!--                            <li class="flex items-center">-->
<!--                                <CheckCircleIcon class="h-5 w-5 text-green-500 mr-2" />-->
<!--                                <span>API access</span>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

            <!-- Trust Note -->
            <div class="mt-12 text-center text-sm text-primary-500">
                <p>2,500+ businesses trust Zelvra. No credit card required to start.</p>
            </div>
        </div>
    </section>
</template>

<script setup>
import { CheckCircleIcon, ShieldCheckIcon } from '@heroicons/vue/24/outline'
import { ref } from 'vue'

const billingCycle = ref('monthly')
</script>
