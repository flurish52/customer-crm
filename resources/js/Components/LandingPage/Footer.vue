<template>
    <footer class="bg-gray-900 text-tertiary pt-16 pb-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Main Footer Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-10 mb-12">
                <!-- Logo + Tagline -->
                <div class="col-span-2 lg:col-span-1">
                    <div class="flex items-center mb-4">
                        <!-- SVG Logo Inline -->
                        <svg class="h-8 w-auto text-tertiary-50" viewBox="0 0 120 120" fill="currentColor">
                            <path d="M60 0L120 30V90L60 120L0 90V30L60 0Z" />
                            <path d="M60 30L90 45V75L60 90L30 75V45L60 30Z" fill="#111827"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold">Entroly</span>
                    </div>
                    <p class="text-primary-200 text-sm mb-4">
                        Client management simplified for professionals who mean business.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-primary-300 hover:text-tertiary-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-primary-300 hover:text-tertiary-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Rest of footer content remains the same -->
                <!-- Quick Links -->
                <div>
                    <h3 class="text-sm font-semibold text-tertiary-100 uppercase tracking-wider mb-4">Product</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-primary-200 hover:text-tertiary-50 transition-colors text-sm">Features</a></li>
                        <li><a href="#" class="text-primary-200 hover:text-tertiary-50 transition-colors text-sm">Pricing</a></li>
                        <li><a href="#" class="text-primary-200 hover:text-tertiary-50 transition-colors text-sm">Integrations</a></li>
                        <li><a href="#" class="text-primary-200 hover:text-tertiary-50 transition-colors text-sm">What's New</a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div>
                    <h3 class="text-sm font-semibold text-tertiary-100 uppercase tracking-wider mb-4">Resources</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-primary-200 hover:text-tertiary-50 transition-colors text-sm">Blog</a></li>
                        <li><a href="#" class="text-primary-200 hover:text-tertiary-50 transition-colors text-sm">Guides</a></li>
                        <li><a href="#" class="text-primary-200 hover:text-tertiary-50 transition-colors text-sm">Help Center</a></li>
                        <li><a href="#" class="text-primary-200 hover:text-tertiary-50 transition-colors text-sm">API Docs</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h3 class="text-sm font-semibold text-tertiary-100 uppercase tracking-wider mb-4">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-primary-200 hover:text-tertiary-50 transition-colors text-sm">About</a></li>
                        <li><a href="#" class="text-primary-200 hover:text-tertiary-50 transition-colors text-sm">Careers</a></li>
                        <li><a href="#" class="text-primary-200 hover:text-tertiary-50 transition-colors text-sm">Contact</a></li>
                    </ul>
                </div>

                <!-- CTA Column -->
                <div class="col-span-2 md:col-span-4 lg:col-span-1 lg:col-start-5">
                    <h3 class="text-sm font-semibold text-tertiary-100 uppercase tracking-wider mb-4">Get Started</h3>
                    <p class="text-primary-200 text-sm mb-4">
                        Join thousands of professionals managing clients smarter.
                    </p>
                    <Link href="/register" class="w-full bg-tertiary-50 hover:bg-tertiary-100 text-primary-800 py-2 px-4 rounded-md text-sm font-medium transition-colors">
                        Sign Up Free
                    </Link>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-primary-700 my-8"></div>

            <!-- Bottom Row -->
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-sm text-primary-300 mb-4 md:mb-0">
                    ©{{dayjs(Date()).format('YYYY')}} Entroly. All rights reserved.
                </div>
                <div class="flex space-x-6">
                    <Link href="#" class="text-sm text-primary-300 hover:text-tertiary-50 transition-colors">Privacy</Link>
                    <Link href="#" class="text-sm text-primary-300 hover:text-tertiary-50 transition-colors">Terms</Link>
                    <Link href="#" class="text-sm text-primary-300 hover:text-tertiary-50 transition-colors">Cookies</Link>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import dayjs from "dayjs";
</script>
