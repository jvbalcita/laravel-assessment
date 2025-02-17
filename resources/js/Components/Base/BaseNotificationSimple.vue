<script setup>
import { CheckCircleIcon, ExclamationCircleIcon } from '@heroicons/vue/24/outline';
import { XMarkIcon } from '@heroicons/vue/20/solid';

defineProps({
    notifications: {
        type: Array,
        default: () => [],
    },
    show: {
        type: Boolean,
        default: false,
    },
    type: {
        type: String,
        default: 'success',
    },
    message: {
        type: String,
        default: '',
    },
});

defineEmits(['closeNotification']);
</script>

<template>
    <div
        aria-live="assertive"
        class="z-50 pointer-events-none fixed inset-0 flex items-end p-4 sm:items-start sm:p-2"
    >
        <div class="flex w-full flex-col items-center space-y-2 sm:items-end">
            <transition-group
                enter-active-class="transform ease-out duration-300 transition"
                enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <template
                    v-for="(notification, index) in notifications"
                    :key="index"
                >
                    <div
                        v-if="notification"
                        class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-black ring-opacity-5 dark:bg-slate-950"
                    >
                        <div class="p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <CheckCircleIcon
                                        v-if="notification.type === 'success'"
                                        class="h-6 w-6 text-green-400"
                                        aria-hidden="true"
                                    />
                                    <ExclamationCircleIcon
                                        v-else
                                        class="h-6 w-6"
                                        aria-hidden="true"
                                        :class="notification.type === 'warning' ? 'text-amber-400' : 'text-red-500'"
                                    />
                                </div>
                                <div class="ml-3 w-0 flex-1 pt-0.5">
                                    <p class="text-sm font-base text-gray-700 dark:text-white">
                                        {{ notification.message }}
                                    </p>
                                </div>
                                <div class="ml-4 flex flex-shrink-0">
                                    <div
                                        type="button"
                                        class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-slate-950"
                                        @click="$emit('closeNotification', index)"
                                    >
                                        <span class="sr-only">Close</span>
                                        <XMarkIcon
                                            class="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </transition-group>
        </div>
    </div>
</template>
