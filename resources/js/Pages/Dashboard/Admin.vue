<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    stats: Object
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                School Overview
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div class="bg-white border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                        <div class="text-sm font-medium text-gray-500 uppercase">Total Students</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ stats.students_count }}</div>
                    </div>

                    <div class="bg-white border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                        <div class="text-sm font-medium text-gray-500 uppercase">Total Teachers</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ stats.teachers_count }}</div>
                    </div>

                    <div class="bg-white border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                        <div class="text-sm font-medium text-gray-500 uppercase">Total Classes</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ stats.classes_count }}</div>
                    </div>
                </div>

                <div class="mt-8 bg-white border border-gray-200 overflow-hidden dark:bg-gray-800 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-200 pb-4 dark:border-gray-700">System Logs</h3>
                        <div class="mt-4 text-gray-600 dark:text-gray-400">
                            No recent activity found.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
