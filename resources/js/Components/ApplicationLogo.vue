<template>
    <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Circular container -->
        <circle cx="100" cy="100" r="95" stroke="url(#borderGradient)" stroke-width="2"/>

        <!-- Abstract people/connection symbol -->
        <path d="M80 120C80 106.745 90.7452 96 104 96C117.255 96 128 106.745 128 120" stroke="url(#iconGradient)" stroke-width="4" stroke-linecap="round"/>
        <path d="M104 96V70M104 70C104 62.268 97.732 56 90 56C82.268 56 76 62.268 76 70C76 77.732 82.268 84 90 84" stroke="url(#iconGradient)" stroke-width="4" stroke-linecap="round"/>
        <path d="M104 70C104 62.268 110.268 56 118 56C125.732 56 132 62.268 132 70C132 77.732 125.732 84 118 84" stroke="url(#iconGradient)" stroke-width="4" stroke-linecap="round"/>

        <!-- Text -->
        <text x="100" y="150" text-anchor="middle" font-family="'Inter', sans-serif" font-weight="600" font-size="24" fill="url(#textGradient)">ZELVRA</text>

        <!-- Gradients -->
        <defs>
            <linearGradient id="borderGradient" x1="0" y1="0" x2="200" y2="200" gradientUnits="userSpaceOnUse">
                <stop stop-color="theme('colors.primary.light')"/>
                <stop offset="1" stop-color="theme('colors.primary.dark')"/>
            </linearGradient>
            <linearGradient id="iconGradient" x1="76" y1="56" x2="132" y2="120" gradientUnits="userSpaceOnUse">
                <stop stop-color="theme('colors.secondary.light')"/>
                <stop offset="1" stop-color="theme('colors.secondary.dark')"/>
            </linearGradient>
            <linearGradient id="textGradient" x1="80" y1="140" x2="120" y2="160" gradientUnits="userSpaceOnUse">
                <stop stop-color="theme('colors.tertiary.light')"/>
                <stop offset="1" stop-color="theme('colors.tertiary.dark')"/>
            </linearGradient>
        </defs>
    </svg>
</template>
