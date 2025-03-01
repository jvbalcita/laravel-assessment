<script setup>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { XMarkIcon, ArrowLeftIcon } from '@heroicons/vue/24/outline';

defineProps({
    title: {
        type: String,
        default: 'Panel Title',
    },
    description: {
        type: String,
        default: 'Panel Description',
    },
    open: {
        type: Boolean,
        required: true,
    },
    isBack: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['close', 'back']);
</script>

<template>
    <TransitionRoot
        as="template"
        :show="open"
    >
        <Dialog
            as="div"
            class="relative z-50"
            @close="$emit('close', true)"
        >
            <!--Background Transition-->
            <TransitionChild
                as="template"
                enter="ease-in-out duration-500"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in-out duration-500"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-slate-500 bg-opacity-10 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <TransitionChild
                            as="template"
                            enter="transform transition ease-in-out duration-500 sm:duration-400"
                            enter-from="translate-x-full"
                            enter-to="translate-x-0"
                            leave="transform transition ease-in-out duration-500 sm:duration-400"
                            leave-from="translate-x-0"
                            leave-to="translate-x-full"
                        >
                            <DialogPanel class="pointer-events-auto w-screen max-w-md">
                                <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl dark:bg-slate-900">
                                    <div class="px-4 sm:px-6">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center">
                                                <button
                                                    v-if="isBack"
                                                    type="button"
                                                    class="mr-1 rounded-md bg-white text-gray-400 hover:text-gray-500 transition-all duration-150 focus:outline-none focus:ring-4 focus:ring-primary focus:ring-opacity-30 focus:border-primary focus:border-opacity-40 dark:bg-slate-900"
                                                    @click="$emit('back', true)"
                                                >
                                                    <span class="sr-only">Back panel</span>
                                                    <ArrowLeftIcon
                                                        class="h-6 w-6"
                                                        aria-hidden="true"
                                                    />
                                                </button>
                                                <DialogTitle class="text-lg font-medium text-gray-900 dark:text-white">
                                                    {{ title }}
                                                </DialogTitle>
                                            </div>
                                            <div class="flex h-7 items-center">
                                                <button
                                                    type="button"
                                                    class="rounded-md bg-white text-gray-400 hover:text-gray-500 transition-all duration-150 focus:outline-none focus:ring-4 focus:ring-primary focus:ring-opacity-30 focus:border-primary focus:border-opacity-40 dark:bg-slate-900"
                                                    @click="$emit('close', true)"
                                                >
                                                    <span class="sr-only">Close panel</span>
                                                    <XMarkIcon
                                                        class="h-6 w-6"
                                                        aria-hidden="true"
                                                    />
                                                </button>
                                            </div>
                                        </div>
                                        <div
                                            v-if="description"
                                            class="py-2 text-sm dark:text-gray-400"
                                        >
                                            {{ description }}
                                        </div>
                                    </div>
                                    <div class="relative mt-6 flex-1 px-4 sm:px-6">
                                        <!-- Replace with your content -->
                                        <div class="absolute inset-0 px-4 sm:px-6">
                                            <div
                                                class="h-full"
                                                aria-hidden="true"
                                            >
                                                <slot />
                                            </div>
                                        </div>
                                        <!-- /End replace -->
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
