<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    teacher: Object
});
</script>

<template>
    <Head title="Teacher Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Welcome, {{ teacher?.user?.name || 'Teacher' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-medium border-b border-gray-100 pb-4 dark:border-gray-700">Assigned Classes</h3>
                    <div class="mt-4 text-sm text-gray-500">
                        No classes assigned yet.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
