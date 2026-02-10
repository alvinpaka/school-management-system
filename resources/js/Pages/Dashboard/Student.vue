<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    student: Object
});
</script>

<template>
    <Head title="Student Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                My Studies
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-4 dark:border-gray-700">
                        <h3 class="text-lg font-medium">Academic Info</h3>
                        <span class="text-sm font-bold bg-blue-100 text-blue-800 px-3 py-1 dark:bg-blue-900 dark:text-blue-100">
                            {{ student?.academic_class?.name || 'N/A' }} - {{ student?.section?.name || 'N/A' }}
                        </span>
                    </div>
                    <div class="mt-6">
                        <div class="text-sm text-gray-500">Admission Number</div>
                        <div class="text-lg font-mono">{{ student?.admission_number || 'PENDING' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
