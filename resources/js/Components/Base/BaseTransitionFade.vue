<template>
    <Transition
        name="fade"
        mode="out-in"
    >
        <slot />
    </Transition>
</template>

<style scoped>
.fade-enter-active {
    transition: all 0.3s ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
