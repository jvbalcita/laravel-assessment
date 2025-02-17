<script setup>
import {useNotificationStore} from '@/Stores/notificationStore';
import {ref, computed, onMounted} from 'vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BaseTableMultipleColumn from "@/Components/Base/BaseTableMultipleColumn.vue";
import BasePaginator from "@/Components/Base/BasePaginator.vue";
import BaseTableEmptyState from "@/Components/Base/BaseTableEmptyState.vue";
import { ExclamationCircleIcon, InfoCircleIcon, UsersGroupIcon } from 'vue-tabler-icons';
import BaseModalConfirmation from "@/Components/Base/BaseModalConfirmation.vue";

const notificationStore = useNotificationStore();

const props = defineProps({
    users: {
        type: [Array, Object],
        required: true
    },
});

const users = computed(() => props.users);

const showUserDeleteModal = ref(false);
const showUserRestoreModal = ref(false);

const renderKey = ref(0);
const openSlider = ref(false);
const selectedItem = ref({
    type: 'new',
    index: 0,
    data: {
        id: '',
    },
});

const form = useForm({
    id: '',
});


const tableData = computed(() => {
    return users.value.data.map((users) => {
        return {
            ...users,
        };
    });
});

const tableHeaders = [
    {
        text: 'Full Name',
        type: 'full_name',
    },
    {
        text: 'Middle Initial',
        type: 'middle_initial',
    },
    {
        text: 'Type',
        type: 'type',
    },
];

// Functions
function restoreUser(index) {
    selectedItem.value.data = tableData.value[index];

    showUserRestoreModal.value = true;
}

function deleteUser(index) {
    selectedItem.value.data = tableData.value[index];

    showUserDeleteModal.value = true;
}

function confirmUserRestore() {
    form.patch(route('users-archived.restore', selectedItem.value.data.id), {
        onSuccess: () => {
            showUserRestoreModal.value = false;

            notificationStore.invokeAddNotification({
                type: 'success',
                message: 'User restored successfully.',
            });

            router.reload({only: ['users']});
        },
    });
}

function confirmUserDeletion() {
    form.delete(route('users-archived.delete', selectedItem.value.data.id), {
        onSuccess: () => {
            showUserDeleteModal.value = false;

            notificationStore.invokeAddNotification({
                type: 'success',
                message: 'User permanently deleted successfully.',
            });

            router.reload({only: ['users']});
        },
    });
}


onMounted(() => {
    checkTableLastPage(props.users);

    renderKey.value++;
});

function checkCloseStatus() {
    openSlider.value = false;
    showUserRestoreModal.value = false;
    showUserDeleteModal.value = false;
}

function checkTableLastPage(table) {
    if (table.current_page > table.last_page) {
        router.visit(table.last_page_url);
    }
}
</script>

<template>
    <Head>
        <title>Users</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>
            Deleted Users
        </template>

        <div class="px-4 sm:px-6 lg:px-8 p-2">
            <div class="card mt-6">
                <template v-if="tableData.length > 0">
                    <BaseTableMultipleColumn
                        :table-data="tableData"
                        :table-headers="tableHeaders"
                        size="xs"
                        :can-edit="false"
                        :custom-button="true"
                        custom-button-name="Restore"
                        @clicked="restoreUser"
                        @deleted="deleteUser"
                    />
                    <BasePaginator :data="users"/>
                </template>
                <div
                    v-else
                    class="mt-6"
                >
                    <BaseTableEmptyState
                        title="No Deleted Users Found"
                        description="You will see users here after they have been deleted."
                        :is-button-enabled="true"
                    >
                        <template #icon>
                            <UsersGroupIcon class="mx-auto h-12 w-12 text-gray-400 stroke-1"/>
                        </template>
                    </BaseTableEmptyState>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <div v-if="showUserRestoreModal">
        <BaseModalConfirmation
            :open="showUserRestoreModal"
            icon="bg-blue-100"
            @close="checkCloseStatus"
        >
            <template #title>
                Restore User
            </template>

            <template #description>
                Are you sure you want to restore this user?
            </template>

            <template #icon>
                <InfoCircleIcon
                    class="h-6 w-6 text-blue-600"
                    aria-hidden="true"
                />
            </template>

            <template #actions>
                <div class="mt-5 sm:ml-10 sm:mt-4 sm:flex sm:pl-4">
                    <button
                        type="button"
                        class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:w-auto"
                        @click="confirmUserRestore"
                    >
                        Yes, restore it
                    </button>
                    <button
                        ref="cancelButtonRef"
                        type="button"
                        class="inline-flex items-center rounded-md bg-slate-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-600 sm:ml-3 sm:mt-0 sm:w-auto"
                        @click="checkCloseStatus"
                    >
                        No, cancel
                    </button>
                </div>
            </template>
        </BaseModalConfirmation>
    </div>

    <div v-if="showUserDeleteModal">
        <BaseModalConfirmation
            :open="showUserDeleteModal"
            icon="bg-red-100"
            @close="checkCloseStatus"
        >
            <template #title>
                Permanently Delete User
            </template>

            <template #description>
                Are you sure you want to permanently delete this user?
            </template>

            <template #icon>
                <ExclamationCircleIcon
                    class="h-6 w-6 text-red-600"
                    aria-hidden="true"
                />
            </template>

            <template #actions>
                <div class="mt-5 sm:ml-10 sm:mt-4 sm:flex sm:pl-4">
                    <button
                        type="button"
                        class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto"
                        @click="confirmUserDeletion"
                    >
                        Yes, delete it
                    </button>
                    <button
                        ref="cancelButtonRef"
                        type="button"
                        class="inline-flex items-center rounded-md bg-slate-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-600 sm:ml-3 sm:mt-0 sm:w-auto"
                        @click="checkCloseStatus"
                    >
                        No, cancel
                    </button>
                </div>
            </template>
        </BaseModalConfirmation>
    </div>
</template>
