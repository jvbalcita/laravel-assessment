<script setup>
import { computed } from 'vue';

const props = defineProps({
    color: {
        type: String,
        default: 'primary',
    },
    type: {
        type: String,
        default: 'button',
    },
});

defineEmits(['click']);

// Map color prop to corresponding class string
const colorClassMap = {
    secondary: 'bg-secondary text-gray-700 border-gray-300 hover:text-blue-500 active:bg-gray-100 dark:border-gray-700 dark:bg-slate-900 dark:text-gray-200 dark:hover:text-blue-500',
    danger: 'bg-red-600 text-white hover:bg-red-500 active:brightness-95 focus-visible:outline-red-600',
    primary: 'bg-gray-800 text-white hover:bg-gray-700 focus:bg-gray-700 active:brightness-95 focus-visible:outline-gray-600',
};

const color = computed(() => colorClassMap[props.color]);
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center rounded-md px-3 py-2 text-sm font-semibold shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2"
        :class="[color]"
        @click="$emit('click')"
    >
        <slot />
    </button>
</template>
