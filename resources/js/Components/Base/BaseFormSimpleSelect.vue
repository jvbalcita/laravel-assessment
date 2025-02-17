<script setup>
import { onMounted, ref, watch } from 'vue';
import { Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    items: {
        type: Array,
        default() {
            return [
                { id: 1, name: 'Item 1', icon: '' },
                { id: 2, name: 'Item 2', icon: '' },
                { id: 3, name: 'Item 3', icon: '' },
            ];
        },
    },
    initialSelectedItem: {
        type: Object,
        default: null,
    },
    label: {
        type: String,
        default: '',
    },
    withIcon: {
        type: Boolean,
        default: false,
    },
    hasError: {
        type: [String, Boolean],
        default: false,
    },
    customCss: {
        type: String,
        default: null,
    },
    optional: {
        type: Boolean,
        default: false,
    },
});

const initialDefaultItem = { id: 0, name: 'Select One', icon: '' };
const initialDefault = ref(false);

const selectedItem = ref(props.initialSelectedItem);

const emit = defineEmits(['selectedItem', 'update:modelValue']);

watch(() => selectedItem.value, (newVal) => {
    emit('selectedItem', newVal);
    emit('update:modelValue', newVal);
});

// Add a watch function for the initialSelectedItem prop
watch(() => props.initialSelectedItem, (newVal) => {
    if (newVal) {
        selectedItem.value = newVal;
    } else {
        selectedItem.value = initialDefaultItem;
    }
}, { immediate: true });

// Handle the initial default value after the component is mounted
onMounted(() => {
    if (!selectedItem.value) {
        initialDefault.value = true;
        selectedItem.value = props.initialSelectedItem || initialDefaultItem;
    }
});
</script>

<template>
    <div>
        <Listbox v-model="selectedItem">
            <ListboxLabel
                v-if="label"
                class="block text-sm font-medium text-gray-700 mb-1 dark:text-white"
                :class="{ 'flex items-center justify-between': optional }"
            >
                {{ label }}
                <span
                    v-if="optional"
                    class="text-xs text-gray-500"
                >Optional</span>
            </ListboxLabel>
            <div class="relative">
                <ListboxButton
                    class="relative w-full bg-white rounded-md shadow-sm pl-3 pr-10 py-2 text-left text-base cursor-default border border-gray-300 transition-all duration-150 focus:outline-none focus:ring-4 focus:ring-primary focus:ring-opacity-30 focus:border-primary focus:border-opacity-40 dark:bg-gray-800 dark:text-white dark:border-slate-700"
                    :class="[
                        hasError
                            ? 'text-red-300 border-red-400 placeholder-red-300/90 focus:ring-red-500 focus:ring-opacity-20 focus:border-red-500 focus:border-opacity-40'
                            : 'text-gray-700 border-gray-300 placeholder-slate-400/90 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40',
                        customCss
                    ]"
                >
                    <span class="flex items-center text-sm space-x-2">
                        <component
                            :is="selectedItem.icon"
                            v-if="withIcon"
                            class="h-5 w-5 stroke-[1.5]"
                        />
                        <span class="block truncate">
                            {{ selectedItem ? selectedItem.name : 'Select One' }}
                        </span>
                    </span>
                    <span class="ml-3 absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <ChevronUpDownIcon
                            class="h-5 w-5 text-gray-400"
                            aria-hidden="true"
                        />
                    </span>
                </ListboxButton>

                <transition
                    leave-active-class="transition ease-in duration-100"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <ListboxOptions
                        class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-56 rounded-md py-1 text-sm ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none dark:bg-slate-900 dark:text-slate-200 dark:border-slate-700"
                    >
                        <ListboxOption
                            v-for="item in items"
                            :key="item.name"
                            v-slot="{ active, selected }"
                            as="template"
                            :value="item"
                        >
                            <li :class="[active ? 'text-white bg-primary' : 'text-gray-700', 'cursor-default select-none relative py-2 pl-3 dark:text-slate-200']">
                                <div class="flex items-center space-x-2">
                                    <component
                                        :is="item.icon"
                                        v-if="withIcon"
                                        class="flex-shrink-0 h-5 w-5 rounded-full stroke-[1.5]"
                                    />
                                    <span :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">
                                        {{ item.name }}
                                    </span>
                                </div>

                                <span
                                    v-if="selected"
                                    :class="[active ? 'text-white' : 'text-primary', 'absolute inset-y-0 right-0 flex items-center pr-4']"
                                >
                                    <CheckIcon
                                        class="h-5 w-5"
                                        aria-hidden="true"
                                    />
                                </span>
                            </li>
                        </ListboxOption>
                    </ListboxOptions>
                </transition>
            </div>
        </Listbox>
    </div>
</template>
