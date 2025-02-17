<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const { props } = usePage();
const user = props.auth.user;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            Dashboard
        </template>

        <div class="py-10">
            <div class="mx-auto max-w-9xl sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900">Welcome, {{ user.first_name }}</h2>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
