<script setup>
import {useNotificationStore} from '@/Stores/notificationStore';
import {ref, computed, onMounted} from 'vue';
import {useForm, Head, router} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BaseTableMultipleColumn from "@/Components/Base/BaseTableMultipleColumn.vue";
import BasePaginator from "@/Components/Base/BasePaginator.vue";
import BaseTableEmptyState from "@/Components/Base/BaseTableEmptyState.vue";
import BaseActionButton from "@/Components/Base/BaseActionButton.vue";
import {PlusIcon, UsersGroupIcon, ExclamationCircleIcon} from 'vue-tabler-icons';
import BaseSlideOver from "@/Components/Base/BaseSlideOver.vue";
import BaseTransitionFade from "@/Components/Base/BaseTransitionFade.vue";
import BaseFormSimpleSelect from "@/Components/Base/BaseFormSimpleSelect.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import BaseFormInputText from "@/Components/Base/BaseFormInputText.vue";
import BaseModalConfirmation from "@/Components/Base/BaseModalConfirmation.vue";

const notificationStore = useNotificationStore();

const props = defineProps({
    users: {
        type: [Array, Object],
        required: true
    },
});

const users = computed(() => props.users);

const types = [{name: 'User'}, {name: 'Admin'}];
const prefixes = [{name: 'Mr'}, {name: 'Mrs'}, {name: 'Ms'}];
const suffixes = [{name: 'Jr.'}, {name: 'Sr.'}, {name: 'III'}, {name: 'IV'}];

const renderKey = ref(0);
const selectedType = ref(types[0]);
const selectedPrefix = ref(prefixes[0]);
const selectedNameSuffix = ref();
const openSlider = ref(false);
const selectedItem = ref({
    type: 'new',
    index: 0,
    data: {
        id: '',
        prefix_name: '',
        first_name: '',
        middle_name: '',
        last_name: '',
        suffix_name: '',
        user_name: '',
        email: '',
        photo: '',
        type: '',
    },
});

const showUserDeleteModal = ref(false);
const sliderTitle = ref('');
const sliderDescription = ref();

const form = useForm({
    prefix_name: '',
    first_name: '',
    middle_name: '',
    last_name: '',
    suffix_name: '',
    user_name: '',
    email: '',
    photo: '',
    type: '',
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
const updateSelected = (type, value) => {
    const updateMap = {
        'type': () => selectedType.value = value,
        'prefix_name': () => selectedPrefix.value = value,
        'suffix_name': () => selectedNameSuffix.value = value
    };

    if (updateMap[type]) {
        updateMap[type]();
        form.clearErrors(type);
    }
};

function addUser() {
    openSlider.value = true;
    resetForm();
    sliderTitle.value = 'New User';
    sliderDescription.value = 'Enter the details for the new user.';
    selectedItem.value.type = 'new';
}

function editUser(index) {
    openSlider.value = true;

    sliderTitle.value = tableData.value[index].full_name;
    sliderDescription.value = 'Edit user details.';
    selectedItem.value.type = 'edit';
    selectedItem.value.index = index;
    selectedItem.value.data = tableData.value[index];

    form.first_name = selectedItem.value.data.first_name;
    form.middle_name = selectedItem.value.data.middle_name;
    form.last_name = selectedItem.value.data.last_name;
    form.user_name = selectedItem.value.data.user_name;
    form.email = selectedItem.value.data.email;
    form.photo = selectedItem.value.data.photo;

    selectedNameSuffix.value = suffixes.find((value) => value.name === selectedItem.value.data.suffix_name);
    selectedPrefix.value = prefixes.find(value => value.name === selectedItem.value.data.prefix_name);
    selectedType.value = types.find(value => value.name === selectedItem.value.data.type);
}

function deleteUser(index) {
    selectedItem.value.data = tableData.value[index];

    showUserDeleteModal.value = true;
}

function viewTrashedUsers() {
    router.visit(route('users-archived.trashed'));
}

function setFormValues() {
    form.type = selectedType.value.name;
    form.prefix_name = selectedPrefix.value.name;
    form.suffix_name = selectedNameSuffix.value.name;
}

async function submit() {
    setFormValues();

    try {
        if (selectedItem.value.type === 'new') {
            form.post(route('users.store'), {
                onSuccess: () => afterSubmitSuccess(),
            });
        } else {
            form.put(route('users.update', selectedItem.value.data.id), {
                preserveScroll: true,
                onSuccess: () => afterSubmitSuccess(),
            });
        }
    } catch (error) {
        notificationStore.invokeAddNotification({
            type: 'error',
            message: 'An error occurred while processing your request.',
        });
    }
}

function afterSubmitSuccess() {
    openSlider.value = false;

    notificationStore.invokeAddNotification({
        type: 'success',
        message: selectedItem.value.type === 'new' ? 'User added successfully.' : 'User updated successfully.',
    });

    router.reload({only: ['users']});
    resetForm();
    form.clearErrors();
}

function confirmUserDeletion() {
    form.delete(route('users.destroy', selectedItem.value.data.id), {
        onSuccess: () => {
            showUserDeleteModal.value = false;

            notificationStore.invokeAddNotification({
                type: 'success',
                message: 'User deleted successfully.',
            });

            router.reload({ only: ['user'] });
        },
    });
}

onMounted(() => {
    checkTableLastPage(props.users);

    renderKey.value++;
});

function resetForm() {
    form.reset();
    form.clearErrors();

    selectedItem.value.data = {
        id: '',
        prefix_name: '',
        first_name: '',
        middle_name: '',
        last_name: '',
        suffix_name: '',
        user_name: '',
        email: '',
        photo: '',
    };

    selectedType.value = null;
    selectedPrefix.value = null;
    selectedNameSuffix.value = null;
}

function checkCloseStatus() {
    openSlider.value = false;
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
            Users
        </template>

        <div class="px-4 sm:px-6 lg:px-8 p-2">
            <div class="card mt-6">
                <div class="flex justify-between mt-6 sm:mt-0 sm:flex-none">
                    <BaseActionButton
                        @click="viewTrashedUsers"
                    >
                        <span>Trashed</span>
                    </BaseActionButton>
                    <BaseActionButton
                        v-show="tableData.length > 0"
                        @click="addUser"
                    >
                        <span>Add User</span>
                    </BaseActionButton>
                </div>
                <template v-if="tableData.length > 0">
                    <BaseTableMultipleColumn
                        :table-data="tableData"
                        :table-headers="tableHeaders"
                        size="xs"
                        @clicked="editUser"
                        @deleted="deleteUser"
                    />
                    <BasePaginator :data="users"/>
                </template>
                <div
                    v-else
                    class="mt-6"
                >
                    <BaseTableEmptyState
                        title="No Users Found"
                        description="Get started by adding new users."
                        :is-button-enabled="true"
                    >
                        <template #icon>
                            <UsersGroupIcon class="mx-auto h-12 w-12 text-gray-400 stroke-1"/>
                        </template>
                        <template #button>
                            <BaseActionButton @click="addUser">
                                <PlusIcon
                                    class="-ml-0.5 mr-1.5 h-5 w-5"
                                    aria-hidden="true"
                                />
                                <span>New User</span>
                            </BaseActionButton>
                        </template>
                    </BaseTableEmptyState>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <BaseSlideOver
        :description="sliderDescription"
        :open="openSlider"
        :title="sliderTitle"
        @close="checkCloseStatus"
    >
        <BaseTransitionFade appear>
            <form
                class="space-y-4"
                @submit.prevent="submit"
                enctype="multipart/form-data"
            >
                <div class="border-b border-gray-900/10 dark:border-gray-800/100 pb-12">
                    <div class="sm:col-span-3">
                        <BaseFormSimpleSelect
                            :key="renderKey"
                            v-model="form.type"
                            :items="types"
                            name="type"
                            label="Type"
                            :has-error="form.errors.type"
                            :initial-selected-item="selectedType"
                            @selected-item="updateSelected('type', $event)"
                        />
                        <InputError :message="form.errors.type"/>
                    </div>

                    <div class="sm:col-span-3 mt-3">
                        <BaseFormSimpleSelect
                            :key="renderKey"
                            v-model="form.prefix_name"
                            :items="prefixes"
                            name="prefix_name"
                            label="Prefix Name"
                            :has-error="form.errors.prefix_name"
                            :initial-selected-item="selectedPrefix"
                            @selected-item="updateSelected('prefix_name', $event)"
                        />
                        <InputError :message="form.errors.prefix_name"/>
                    </div>

                    <div class="sm:col-span-3 mt-3">
                        <InputLabel for="first-name" value="First Name"/>
                        <BaseFormInputText
                            id="first-name"
                            class="mt-1 block w-full"
                            v-model="form.first_name"
                            :has-error="form.errors.first_name"
                        />
                        <InputError :message="form.errors.first_name"/>
                    </div>

                    <div class="sm:col-span-3 mt-3">
                        <InputLabel for="middle-name" value="Middle Name"/>
                        <BaseFormInputText
                            id="middle-name"
                            class="mt-1 block w-full"
                            v-model="form.middle_name"
                            :has-error="form.errors.middle_name"
                        />
                        <InputError :message="form.errors.middle_name"/>
                    </div>

                    <div class="sm:col-span-3 mt-3">
                        <InputLabel for="last-name" value="Last Name"/>
                        <BaseFormInputText
                            id="last-name"
                            class="mt-1 block w-full"
                            v-model="form.last_name"
                            :has-error="form.errors.last_name"
                        />
                        <InputError :message="form.errors.last_name"/>
                    </div>

                    <div class="sm:col-span-3 mt-3">
                        <BaseFormSimpleSelect
                            :key="renderKey"
                            v-model="form.suffix_name"
                            :items="suffixes"
                            name="suffix_name"
                            label="Suffix Name"
                            :has-error="form.errors.suffix_name"
                            :initial-selected-item="selectedNameSuffix"
                            @selected-item="updateSelected('suffix_name', $event)"
                        />
                        <InputError :message="form.errors.suffix_name"/>
                    </div>

                    <div class="sm:col-span-3 mt-3">
                        <InputLabel for="user-name" value="User Name"/>
                        <BaseFormInputText
                            id="user-name"
                            class="mt-1 block w-full"
                            v-model="form.user_name"
                            :has-error="form.errors.user_name"
                        />
                        <InputError :message="form.errors.user_name"/>
                    </div>

                    <div class="sm:col-span-3 mt-3">
                        <InputLabel for="email" value="Email"/>
                        <BaseFormInputText
                            id="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            :has-error="form.errors.email"
                        />
                        <InputError :message="form.errors.email"/>
                    </div>

                    <div class="sm:col-span-3 mt-3">
                        <InputLabel for="photo" value="Photo"/>
                        <BaseFormInputText
                            id="photo"
                            class="mt-1 block w-full"
                            v-model="form.photo"
                            :has-error="form.errors.photo"
                        />
                        <InputError :message="form.errors.photo"/>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        onclick="submit"
                    >
                        <span v-if="selectedItem.type === 'edit'">Save</span>
                        <span v-else>Create</span>
                    </PrimaryButton>
                </div>
            </form>
        </BaseTransitionFade>
    </BaseSlideOver>

    <div v-if="showUserDeleteModal">
        <BaseModalConfirmation
            :open="showUserDeleteModal"
            icon="bg-red-100"
            @close="checkCloseStatus"
        >
            <template #title>
                Delete User
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
