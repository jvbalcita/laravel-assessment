<script setup>
import { computed, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { ChevronLeftIcon, ChevronRightIcon, ChevronsLeftIcon, ChevronsRightIcon } from 'vue-tabler-icons';

const range = ref([]);

const props = defineProps({
    // This follows Laravel pagination metadata https://laraveljsonapi.io/docs/2.0/schemas/pagination.html
    data: {
        type: Object,
        required: true,
    },
});

const propsToWatch = computed(() => {
    return props.data.current_page;
});

const linksToWatch = computed(() => {
    return props.data.links;
});

watch(
    linksToWatch,
    () => {
        organisePageLinks();
    },
    { immediate: true },
);

watch(
    propsToWatch,
    () => {
        organisePageLinks();
    },
    { immediate: true },
);

function organisePageLinks() {
    range.value = [];

    for (let i = 1; i <= props.data.last_page; i++) {
        if (
            i === 1 || // first page
            i === props.data.last_page || // last page
            i === props.data.current_page || // current page
            i === props.data.current_page - 1 || // page before current
            i === props.data.current_page + 1 || // page after current
            (i <= 3 && props.data.current_page < 3) || // "filler" links at start
            (i >= props.data.last_page - 3 && props.data.current_page > props.data.last_page - 3) // "filler" links at end
        ) {
            let index = range.value.length;
            if (index > 0 && range.value[index - 1] < i - 1) {
                range.value.push('...');
            }
            range.value.push(i);
        }
    }
}

function goToPage(page) {
    const url = props.data.links.find(link => link.label.toString() === page.toString()).url;

    router.visit(url, { preserveScroll: true });
}

function getFirstPage() {
    router.visit(props.data.first_page_url, { preserveScroll: true });
}

function getPreviousPage() {
    router.visit(props.data.prev_page_url, { preserveScroll: true });
}

function getNextPage() {
    router.visit(props.data.next_page_url,
        { preserveScroll: true });
}

function getLastPage() {
    router.visit(props.data.last_page_url , { preserveScroll: true });
}
</script>

<template>
    <div class="py-2 flex items-center justify-between">
        <div class="flex flex-col-reverse justify-center gap-y-4 mx-auto sm:mx-0 sm:gap-y-0 sm:flex sm:flex-row sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-center sm:text-sm gap-x-10 text-gray-700 dark:text-gray-500">
                    Showing
                    <span class="font-medium">{{ data.from }}</span>
                    -
                    <span class="font-medium">{{ data.to }}</span>
                    of
                    <span class="font-medium">{{ data.total }}</span>
                    results
                </p>
            </div>
            <div>
                <ul class="relative z-0 inline-flex rounded-md -space-x-px">
                    <li>
                        <button
                            type="button"
                            class="bg-white inline-flex items-center p-2 rounded-md text-sm font-medium text-gray-500 hover:bg-slate-50 active:bg-slate-100 dark:bg-gray-900"
                            :class="data.current_page === 1 ? 'pointer-events-none opacity-25' : ''"
                            @click="getFirstPage"
                        >
                            <span class="sr-only">First Page</span>
                            <ChevronsLeftIcon
                                class="h-5 w-5"
                                aria-hidden="true"
                            />
                        </button>
                    </li>
                    <li>
                        <button
                            type="button"
                            class="bg-white inline-flex items-center p-2 rounded-md text-sm font-medium text-gray-500 hover:bg-slate-50 active:bg-slate-100 dark:bg-gray-900"
                            :class="data.current_page === 1 ? 'pointer-events-none opacity-25' : ''"
                            @click="getPreviousPage"
                        >
                            <span class="sr-only">Previous</span>
                            <ChevronLeftIcon
                                class="h-5 w-5"
                                aria-hidden="true"
                            />
                        </button>
                    </li>
                    <li
                        v-for="(page, index) in range"
                        :key="index"
                        class="bg-white text-gray-500 inline-flex items-center dark:bg-gray-900"
                    >
                        <a
                            v-if="page !== '...'"
                            href="#"
                            class="inline-flex items-center px-4 py-2 text-xs sm:text-sm font-medium rounded-md transition-all duration-150"
                            :class="page === data.current_page ? 'bg-primary text-white shadow-sm rounded-md pointer-events-none' : 'bg-white hover:bg-slate-50 active:bg-slate-100 dark:bg-gray-900'"
                            @click.prevent="goToPage(page)"
                        >
                            {{ page }}
                        </a>
                        <div
                            v-else
                            class="bg-white pointer-events-none relative px-4 py-2 dark:bg-gray-900"
                        >
                            {{ page }}
                        </div>
                    </li>
                    <li>
                        <button
                            type="button"
                            class="bg-white inline-flex items-center p-2 rounded-md text-sm font-medium text-gray-500 hover:bg-slate-50 active:bg-slate-100 dark:bg-gray-900"
                            :class="data.current_page >= data.last_page ? 'pointer-events-none opacity-25' : ''"
                            @click="getNextPage"
                        >
                            <span class="sr-only">Next</span>
                            <ChevronRightIcon
                                class="h-5 w-5"
                                aria-hidden="true"
                            />
                        </button>
                        <button
                            type="button"
                            class="bg-white inline-flex items-center p-2 rounded-md text-sm font-medium text-gray-500 hover:bg-slate-50 active:bg-slate-100 dark:bg-gray-900"
                            :class="data.current_page >= data.last_page ? 'pointer-events-none opacity-25' : ''"
                            @click="getLastPage"
                        >
                            <span class="sr-only">Last Page</span>
                            <ChevronsRightIcon
                                class="h-5 w-5"
                                aria-hidden="true"
                            />
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
