<template>
    <div class="logo-container w-8 h-8">
        <img src="/logo.png" alt="Entroly logo" class="logo w-8 h-8">
    </div>
</template>

<script>
export default {
    name: "Logo",
};
</script>

<style scoped>
.logo-container {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #ffffff; /* Entroly teal */
    padding: 1rem;
    border-radius: 0.5rem;
}

.logo {
    max-width: 150px;
    height: auto;
}
</style>
