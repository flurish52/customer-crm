<template>
<HeroSection />
    <BenefitHeadlines />
<!--    <Pricing />-->
    <ConversionCTRSection />
    <HighConversion />
    <Footer />


</template>

<script setup>
import { onMounted } from 'vue'
import { gsap } from 'gsap'
import { router } from '@inertiajs/vue3'
import HeroSection from "@/Components/LandingPage/HeroSection.vue";
import BenefitHeadlines from "@/Components/LandingPage/BenefitHeadlines.vue";
import Pricing from "@/Components/LandingPage/Pricing.vue";
import ConversionCTRSection from "@/Components/LandingPage/ConversionCTRSection.vue";
import HighConversion from "@/Components/LandingPage/HighConversion.vue";
import Footer from "@/Components/LandingPage/Footer.vue";

const bubbleStyle = (index) => {
    const size = Math.random() * 200 + 50
    const posX = Math.random() * 100
    const posY = Math.random() * 100
    const delay = Math.random() * 5
    const duration = Math.random() * 15 + 10

    return {
        width: `${size}px`,
        height: `${size}px`,
        left: `${posX}%`,
        top: `${posY}%`,
        animation: `float ${duration}s ease-in-out ${delay}s infinite alternate`
    }
}


const navigateUser = (action) => {
    if (action === 'login') {
        router.visit('/login')
    } else if (action === 'register') {
        router.visit('/register')
    }
}

    onMounted(() => {
    gsap.from('h1 span', {
        duration: 1.5,
        opacity: 0,
        y: 50,
        ease: 'power3.out',
        stagger: 0.2
    })

        gsap.from('h1', {
        duration: 1.5,
        opacity: 0,
        y: 50,
        ease: 'power3.out',
        stagger: 0.2
    })

    gsap.from('p', {
    duration: 1,
    opacity: 0,
    y: 20,
    delay: 0.5,
    ease: 'power2.out'
})

    gsap.from('Link', {
    duration: 1,
    // opacity: 0,
    y: 20,
    delay: 0.8,
    stagger: 0.15,
    ease: 'back.out'
})
})

</script>


<style scoped>
@keyframes float {
    0% {
        transform: translateY(0) translateX(0) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) translateX(10px) rotate(5deg);
    }
    100% {
        transform: translateY(0) translateX(0) rotate(0deg);
    }
}

/* Performance optimized animations */
/*@media (prefers-reduced-motion: reduce) {*/
/*    [class*="bubble-"],*/
/*    .lg\\:flex > div {*/
/*        animation: none !important;*/
/*    }*/
/*}*/

</style>
