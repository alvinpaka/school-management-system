<script setup>
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';

defineProps({
    student: Object
});
</script>

<template>
    <Head title="Student Dashboard" />

    <Sidebar>
        <template #header-title>
            My Studies
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Student Info Card -->
            <Card class="mb-8 hover:shadow-lg transition-shadow duration-200">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2 text-gray-900 dark:text-white">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        Academic Information
                    </CardTitle>
                    <CardDescription class="text-gray-600 dark:text-gray-400">
                        Your student profile and academic details
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Admission Number</h4>
                            <p class="text-lg font-mono text-gray-900 dark:text-white font-semibold">{{ student?.admission_number || 'PENDING' }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Class & Section</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <Badge variant="secondary" class="bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                                    {{ student?.academic_class?.name || 'N/A' }} - {{ student?.section?.name || 'N/A' }}
                                </Badge>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Academic Progress -->
            <div class="grid gap-6 md:grid-cols-2 mb-8">
                <Card class="hover:shadow-lg transition-shadow duration-200">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2 text-gray-900 dark:text-white">
                            <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            Recent Grades
                        </CardTitle>
                        <CardDescription class="text-gray-600 dark:text-gray-400">
                            Your latest exam results
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No grades available yet</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Check back after your next exam</p>
                            <Button variant="outline" class="border-blue-600 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                View Upcoming Exams
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <Card class="hover:shadow-lg transition-shadow duration-200">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2 text-gray-900 dark:text-white">
                            <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            Attendance Record
                        </CardTitle>
                        <CardDescription class="text-gray-600 dark:text-gray-400">
                            Your attendance summary
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Present Days</span>
                                <Badge variant="default" class="bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">0</Badge>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Absent Days</span>
                                <Badge variant="destructive" class="bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">0</Badge>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Attendance Rate</span>
                                <Badge variant="secondary" class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">N/A</Badge>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Quick Actions -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</CardTitle>
                    <CardDescription class="text-gray-600 dark:text-gray-400">
                        Common student tasks
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 md:grid-cols-2">
                        <Button variant="outline" class="justify-start h-auto p-4 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-900 dark:text-white">View Timetable</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Check your class schedule</div>
                                </div>
                            </div>
                        </Button>
                        <Button variant="outline" class="justify-start h-auto p-4 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-900 dark:text-white">View Grades</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Check your academic performance</div>
                                </div>
                            </div>
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
