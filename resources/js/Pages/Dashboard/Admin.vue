<script setup>
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';

defineProps({
    stats: Object
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <Sidebar>
        <template #header-title>
            School Overview
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Stats Cards -->
            <div class="grid gap-6 md:grid-cols-3 mb-8">
                <Card class="hover:shadow-lg transition-shadow duration-200">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Students</CardTitle>
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.students_count }}</div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Active students</p>
                    </CardContent>
                </Card>

                <Card class="hover:shadow-lg transition-shadow duration-200">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Teachers</CardTitle>
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.teachers_count }}</div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Active teachers</p>
                    </CardContent>
                </Card>

                <Card class="hover:shadow-lg transition-shadow duration-200">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Classes</CardTitle>
                        <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <svg class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.classes_count }}</div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Active classes</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Quick Actions -->
            <Card class="mb-8">
                <CardHeader>
                    <CardTitle class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</CardTitle>
                    <CardDescription class="text-gray-600 dark:text-gray-400">
                        Manage your school system efficiently
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <Button variant="outline" class="justify-start h-auto p-4 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-900 dark:text-white">Manage Students</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Add, edit, delete students</div>
                                </div>
                            </div>
                        </Button>
                        <Button variant="outline" class="justify-start h-auto p-4 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-900 dark:text-white">Manage Teachers</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Add, edit, delete teachers</div>
                                </div>
                            </div>
                        </Button>
                        <Button variant="outline" class="justify-start h-auto p-4 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-900 dark:text-white">Manage Classes</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Create and manage classes</div>
                                </div>
                            </div>
                        </Button>
                        <Button variant="outline" class="justify-start h-auto p-4 hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors">
                            <div class="flex items-center">
                                <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-900 dark:text-white">Manage Subjects</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Add and manage subjects</div>
                                </div>
                            </div>
                        </Button>
                        <Button variant="outline" class="justify-start h-auto p-4 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <div class="flex items-center">
                                <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-900 dark:text-white">Take Attendance</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Mark student attendance</div>
                                </div>
                            </div>
                        </Button>
                        <Button variant="outline" class="justify-start h-auto p-4 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors">
                            <div class="flex items-center">
                                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-900 dark:text-white">Manage Exams</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Create and manage exams</div>
                                </div>
                            </div>
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- System Activity -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-lg font-semibold text-gray-900 dark:text-white">System Activity</CardTitle>
                    <CardDescription class="text-gray-600 dark:text-gray-400">
                        Recent system activities and logs
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <Badge variant="secondary" class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">System</Badge>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Database backup completed</span>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <Badge variant="secondary" class="bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">Success</Badge>
                                <span class="text-sm text-gray-700 dark:text-gray-300">New student registration</span>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">5 hours ago</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <Badge variant="secondary" class="bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">Update</Badge>
                                <span class="text-sm text-gray-700 dark:text-gray-300">System maintenance completed</span>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">1 day ago</span>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
