<script setup>
defineProps({
    modelValue: {
        type: [String, Number],
        default: '',
    },
    hasError: {
        type: [String, Boolean],
        default: false,
    },
    customCss: {
        type: String,
        default: null,
    },
});

defineEmits(['update:modelValue']);
</script>

<template>
    <input
        type="text"
        class="mt-1 text-sm w-full bg-gray-50 rounded-md shadow-sm transition-all duration-150 focus:bg-white focus:outline-none focus:ring-4 dark:bg-gray-800 dark:text-white dark:border-slate-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200"
        :class="[
            hasError
                ? 'text-red-900 border-red-400 placeholder-red-300/90 focus:ring-red-500 focus:ring-opacity-20 focus:border-red-500 focus:border-opacity-40'
                : 'text-gray-700 border-gray-300 placeholder-slate-400/90 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40',
            customCss
        ]"
        :value="modelValue"
        spellcheck="false"
        @input="$emit('update:modelValue', $event.target.value.trim())"
    >
</template>
