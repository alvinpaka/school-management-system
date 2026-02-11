<script setup>
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';

defineProps({
    teacher: Object
});
</script>

<template>
    <Head title="Teacher Dashboard" />

    <Sidebar>
        <template #header-title>
            Welcome, {{ teacher?.user?.name || 'Teacher' }}
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Teacher Info Card -->
            <Card class="mb-8 hover:shadow-lg transition-shadow duration-200">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2 text-gray-900 dark:text-white">
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        Teacher Profile
                    </CardTitle>
                    <CardDescription class="text-gray-600 dark:text-gray-400">
                        Your teaching information and assigned classes
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Employee ID</h4>
                            <p class="text-lg font-mono text-gray-900 dark:text-white font-semibold">{{ teacher?.employee_id || 'N/A' }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Department</h4>
                            <p class="text-lg text-gray-900 dark:text-white font-semibold">{{ teacher?.department || 'N/A' }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Assigned Classes -->
            <Card class="mb-8">
                <CardHeader>
                    <CardTitle class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        Assigned Classes
                    </CardTitle>
                    <CardDescription class="text-gray-600 dark:text-gray-400">
                        Classes you are currently teaching
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="teacher?.classes && teacher.classes.length > 0" class="space-y-4">
                        <div v-for="classItem in teacher.classes" :key="classItem.id" class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">{{ classItem.name }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ classItem.section?.name || 'No section' }}</p>
                            </div>
                            <Badge variant="secondary" class="bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">{{ classItem.subject_count || 0 }} subjects</Badge>
                        </div>
                    </div>
                    <div v-else class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No classes assigned yet</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Contact the administrator to get class assignments</p>
                        <Button class="bg-blue-600 hover:bg-blue-700 text-white">
                            Contact Administrator
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Quick Actions -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</CardTitle>
                    <CardDescription class="text-gray-600 dark:text-gray-400">
                        Common tasks for teachers
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 md:grid-cols-2">
                        <Button variant="outline" class="justify-start h-auto p-4 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-900 dark:text-white">Take Attendance</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Mark student attendance</div>
                                </div>
                            </div>
                        </Button>
                        <Button variant="outline" class="justify-start h-auto p-4 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-900 dark:text-white">Grade Exams</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Manage exam grades</div>
                                </div>
                            </div>
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
