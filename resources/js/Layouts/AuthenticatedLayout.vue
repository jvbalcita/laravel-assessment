<script setup>
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import { useNotificationStore } from '@/Stores/notificationStore.js';
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import BaseNotificationSimple from "@/Components/Base/BaseNotificationSimple.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const notificationStore = useNotificationStore();

function removeNotification(index) {
    notificationStore.invokeRemoveNotification(index);
}

const page = usePage();
const user = page.props.auth.user;
const navigation = [
    { name: 'Dashboard', href: route('dashboard'), current: route().current('dashboard') },
    { name: 'Users', href: route('users.index'), current: route().current('users.index') },
];
const userNavigation = [
    { name: 'Your Profile', href: route('profile.edit') },
    { name: 'Sign out', href: route('logout'), method: 'post', as: 'button' },
];

const userInitial = computed(() => user.first_name.charAt(0).toUpperCase());

</script>

<template>
    <div class="min-h-full">
        <Disclosure as="nav" class="bg-gray-800" v-slot="{ open }">
            <div class="mx-auto max-w-9xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <img class="size-8" src="https://tailwindui.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company" />
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <Link v-for="item in navigation" :key="item.name" :href="item.href" :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'rounded-md px-3 py-2 text-sm font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</Link>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <!-- Profile dropdown -->
                            <Menu as="div" class="relative ml-3">
                                <div>
                                    <MenuButton class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden">
                                        <span class="absolute -inset-1.5" />
                                        <span class="sr-only">Open user menu</span>
                                        <img v-if="user.avatar" class="size-8 rounded-full" :src="user.avatar" alt="" />
                                        <div v-else class="size-8 rounded-full bg-gray-500 flex items-center justify-center text-white">
                                            {{ userInitial }}
                                        </div>
                                    </MenuButton>
                                </div>
                                <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                                    <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 ring-1 shadow-lg ring-black/5 focus:outline-hidden">
                                        <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                                            <template v-if="item.as === 'button'">
                                                <DropdownLink :href="item.href" method="post" as="button">
                                                    {{ item.name }}
                                                </DropdownLink>
                                            </template>
                                            <template v-else>
                                                <Link :href="item.href" :class="[active ? 'bg-gray-100 outline-hidden' : '', 'block px-4 py-2 text-sm text-gray-700']">{{ item.name }}</Link>
                                            </template>
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <!-- Mobile menu button -->
                        <DisclosureButton class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden">
                            <span class="absolute -inset-0.5" />
                            <span class="sr-only">Open main menu</span>
                            <Bars3Icon v-if="!open" class="block size-6" aria-hidden="true" />
                            <XMarkIcon v-else class="block size-6" aria-hidden="true" />
                        </DisclosureButton>
                    </div>
                </div>
            </div>

            <DisclosurePanel class="md:hidden">
                <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
                    <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href" :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'block rounded-md px-3 py-2 text-base font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</DisclosureButton>
                </div>
                <div class="border-t border-gray-700 pt-4 pb-3">
                    <div class="flex items-center px-5">
                        <div class="shrink-0">
                            <img v-if="user.avatar" class="size-8 rounded-full" :src="user.avatar" alt="" />
                            {{ userInitial }}
                        </div>
                        <div class="ml-3">
                            <div class="text-base/5 font-medium text-white">{{ user.name }}</div>
                            <div class="text-sm font-medium text-gray-400">{{ user.email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1 px-2">
                        <ResponsiveNavLink :href="route('profile.edit')">
                            Profile
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                            Log Out
                        </ResponsiveNavLink>
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>

        <!-- Page Heading -->
        <header
            v-if="$slots.header"
            class="bg-white dark:bg-slate-900 shadow dark:shadow-gray-800/50"
        >
            <div class="mx-auto max-w-9xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-200">
                    <slot name="header" />
                </h1>
            </div>
        </header>

        <main>
            <slot />
        </main>
    </div>

    <BaseNotificationSimple
        :notifications="notificationStore.getNotifications"
        @close-notification="removeNotification"
    />
</template>
