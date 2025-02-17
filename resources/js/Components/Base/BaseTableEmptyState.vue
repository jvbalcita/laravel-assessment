<script setup>

defineProps({
  title: {
    type: String,
    default: 'No Data Found',
  },
  description: {
    type: String,
    default: 'Get started by creating a new data.',
  },
  isButtonEnabled: {
    type: Boolean,
    default: true,
  },
});
</script>

<template>
    <div
        class="overflow-hidden rounded-lg dark:bg-slate-900 border-2 border-dashed border-gray-300 dark:border-gray-600 p-4"
    >
        <div class="px-4 py-5 sm:p-6">
            <div class="text-center">
                <slot name="icon" />

                <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">
                    {{ title }}
                </h3>

                <p class="pt-2 text-gray-400">
                    {{ description }}
                </p>

                <div
                    v-if="isButtonEnabled"
                    class="mt-3"
                >
                    <slot
                        name="button"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
