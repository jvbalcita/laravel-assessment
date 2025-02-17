<script setup>
import { computed } from 'vue';

const props = defineProps({
    size: {
        type: String,
        default: 'sm',
        options: ['xs', 'sm', 'base', 'lg', 'xl'],
    },
    tableHeaders: {
        type: Array,
        default() {
            return [
                {
                    text: 'Name',
                    type: 'name',
                },
                {
                    text: 'Description',
                    type: 'description',
                },
                {
                    text: 'Email',
                    type: 'email',
                },
                {
                    text: 'Enabled',
                    type: 'enabled',
                },
            ];
        },
    },
    tableData: {
        type: Array,
        default() {
            return [
                {
                    name: 'Laravel User',
                    description: 'The best user in the world.',
                    email: 'hello@user.com',
                    enabled: 'true',
                },
                // Add more table data as needed
            ];
        },
    },
    canEdit: {
        type: Boolean,
        default: true,
    },
    customButton: {
        type: Boolean,
        default: false,
    },
    customButtonName: {
        type: String,
        default: 'View',
    },
    canDelete: {
        type: Boolean,
        default: true,
    },
});

defineEmits(['clicked', 'deleted']);

const normalizedHeaders = computed(() => {
    return normalizeTableHeaders();
});

const normalizedTable = computed(() => {
    return normalizeTableData();
});

function normalizeTableHeaders() {
    const normalizedHeaders = [];

    for (const header of props.tableHeaders) {
        normalizedHeaders.push(header);
    }

    return normalizedHeaders;
}

function normalizeTableData() {
    const normalizedTable = [];

    for (const data of props.tableData) {
        const tableRow = [];

        for (const header of props.tableHeaders) {
            if (header.type in data) {
                tableRow.push(data[header.type]);
            } else {
                tableRow.push('');
            }
        }

        normalizedTable.push(tableRow);
    }

    return normalizedTable;
}
</script>

<template>
    <div :class="`text-${size}`">
        <div class="mt-6 overflow-hidden overflow-x-auto shadow-sm ring-1 ring-black ring-opacity-5 md:mx-0 rounded-lg dark:ring-opacity-40">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800 dark:bg-opacity-80">
                <tr>
                    <th
                        v-for="(header, index) in normalizedHeaders"
                        :key="index"
                        scope="col"
                        class="py-3.5 pl-4 pr-3 text-left font-medium text-gray-900 tracking-wider sm:pl-6 dark:text-white"
                    >
                        {{ header.text }}
                    </th>
                    <th
                        scope="col"
                        class="relative py-3.5 pl-3 pr-4 sm:pr-6"
                    >
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:bg-opacity-85 dark:divide-gray-700">
                <tr
                    v-for="(item, index) in normalizedTable"
                    :key="index"
                    class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-100"
                >
                    <td
                        v-for="(cell, cellIndex) in item"
                        :key="cellIndex"
                        class="relative py-4 pl-4 pr-3 text-sm sm:pl-6 dark:border-gray-700 dark:text-gray-400"
                    >
                        {{ cell }}
                    </td>
                    <td class="py-4 pl-3 pr-4 text-right sm:pr-6">
                        <div class="flex justify-end space-x-2">
                            <button
                                v-if="canEdit"
                                type="button"
                                class="text-primary hover:brightness-110"
                                @click="$emit('clicked', index)"
                            >
                                Edit
                            </button>

                            <div
                                v-if="canEdit && (customButton || canDelete)"
                                class="lg:block w-px h-6 bg-gray-200 dark:bg-gray-500 mx-2"
                                aria-hidden="true"
                            />

                            <button
                                v-if="customButton"
                                type="button"
                                class="text-primary hover:brightness-110"
                                @click="$emit('clicked', index)"
                            >
                                {{ customButtonName }}
                            </button>

                            <div
                                v-if="customButton && canDelete"
                                class="lg:block w-px h-6 bg-gray-200 dark:bg-gray-500 mx-2"
                                aria-hidden="true"
                            />

                            <button
                                v-if="canDelete"
                                type="button"
                                class="text-red-600 hover:brightness-110"
                                @click="$emit('deleted', index)"
                            >
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
