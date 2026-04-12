<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';
import Header from '@/Components/Header.vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Link, router } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-background text-foreground transition-colors duration-500">
            <nav
                class="border-b border-border bg-card transition-colors duration-500"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-foreground"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    Dashboard
                                </NavLink>
                                <NavLink
                                    v-if="$page.props.auth.user.roles.includes('admin')"
                                    :href="route('students.index')"
                                    :active="route().current('students.*')"
                                >
                                    Students
                                </NavLink>
                                <NavLink
                                    v-if="$page.props.auth.user.roles.includes('admin')"
                                    :href="route('teachers.index')"
                                    :active="route().current('teachers.*')"
                                >
                                    Teachers
                                </NavLink>
                                <NavLink
                                    v-if="$page.props.auth.user.roles.includes('admin')"
                                    :href="route('classes.index')"
                                    :active="route().current('classes.*')"
                                >
                                    Classes
                                </NavLink>
                                <NavLink
                                    v-if="$page.props.auth.user.roles.includes('admin')"
                                    :href="route('subjects.index')"
                                    :active="route().current('subjects.*')"
                                >
                                    Subjects
                                </NavLink>
                                <NavLink
                                    v-if="$page.props.auth.user.roles.includes('admin') || $page.props.auth.user.roles.includes('teacher')"
                                    :href="route('attendance.index')"
                                    :active="route().current('attendance.*')"
                                >
                                    Attendance
                                </NavLink>
                                <NavLink
                                    v-if="$page.props.auth.user.roles.includes('admin')"
                                    :href="route('exams.index')"
                                    :active="route().current('exams.*')"
                                >
                                    Exams
                                </NavLink>
                                <NavLink
                                    v-if="$page.props.auth.user.roles.includes('admin')"
                                    :href="route('fees.index')"
                                    :active="route().current('fees.*')"
                                >
                                    Fees
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center sm:space-x-3">
                            <!-- Dark Mode Toggle -->
                            <DarkModeToggle variant="ghost" />
                            
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" class="inline-flex items-center rounded-md border border-transparent bg-background px-3 py-2 text-sm font-medium leading-4 text-muted-foreground transition duration-150 ease-in-out hover:bg-accent hover:text-accent-foreground focus:outline-none">
                                            {{ $page.props.auth.user.name }}
                                            <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end" class="w-48 bg-popover text-popover-foreground border-border">
                                        <DropdownMenuItem as-child>
                                            <Link :href="route('profile.edit')" class="w-full cursor-pointer">
                                                Profile
                                            </Link>
                                        </DropdownMenuItem>
                                        <DropdownMenuItem @click="router.post(route('logout'))" class="cursor-pointer">
                                            Log Out
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-muted-foreground transition duration-150 ease-in-out hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2 bg-card">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="$page.props.auth.user.roles.includes('admin')"
                            :href="route('students.index')"
                            :active="route().current('students.*')"
                        >
                            Students
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="$page.props.auth.user.roles.includes('admin')"
                            :href="route('teachers.index')"
                            :active="route().current('teachers.*')"
                        >
                            Teachers
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="$page.props.auth.user.roles.includes('admin')"
                            :href="route('classes.index')"
                            :active="route().current('classes.*')"
                        >
                            Classes
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="$page.props.auth.user.roles.includes('admin')"
                            :href="route('subjects.index')"
                            :active="route().current('subjects.*')"
                        >
                            Subjects
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="$page.props.auth.user.roles.includes('admin') || $page.props.auth.user.roles.includes('teacher')"
                            :href="route('attendance.index')"
                            :active="route().current('attendance.*')"
                        >
                            Attendance
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="$page.props.auth.user.roles.includes('admin')"
                            :href="route('exams.index')"
                            :active="route().current('exams.*')"
                        >
                            Exams
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="$page.props.auth.user.roles.includes('admin')"
                            :href="route('fees.index')"
                            :active="route().current('fees.*')"
                        >
                            Fees
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-border pb-1 pt-4 bg-card"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-foreground"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-muted-foreground">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <Header v-if="$slots.header">
                <template #header-title>
                    <slot name="header" />
                </template>
            </Header>

            <!-- Page Content -->
            <main class="bg-background">
                <slot />
            </main>
        </div>
    </div>
</template>
