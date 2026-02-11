<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
    Calendar,
    Clock,
    BookOpen,
    Users,
    MapPin
} from 'lucide-vue-next';

defineProps({
    timetable: Array,
    classes: Array
});
</script>

<template>
    <Head title="Timetable Management" />

    <Sidebar>
        <template #header-title>
            Timetable Management
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Class Timetable</h2>
                        <p class="text-gray-600 dark:text-gray-400">View and manage class schedules</p>
                    </div>
                </div>
            </div>

            <!-- Timetable Overview -->
            <Card class="mb-6">
                <CardHeader>
                    <CardTitle>Weekly Schedule</CardTitle>
                    <CardDescription>Current week timetable overview</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="text-center py-12">
                        <Calendar class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Timetable Module</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Comprehensive schedule management system</p>
                        <div class="space-y-2 text-left max-w-md mx-auto">
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <CheckCircle class="w-4 h-4 mr-2 text-green-500" />
                                Weekly timetable view
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <CheckCircle class="w-4 h-4 mr-2 text-green-500" />
                                Class scheduling
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <CheckCircle class="w-4 h-4 mr-2 text-green-500" />
                                Teacher assignments
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <CheckCircle class="w-4 h-4 mr-2 text-green-500" />
                                Room management
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <CheckCircle class="w-4 h-4 mr-2 text-green-500" />
                                Conflict detection
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Today's Schedule -->
            <Card>
                <CardHeader>
                    <CardTitle>Today's Schedule</CardTitle>
                    <CardDescription>Classes scheduled for today</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <BookOpen class="w-5 h-5 text-white" />
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Mathematics</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Class 10A - Room 201</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">8:00 - 9:00 AM</div>
                                <Badge variant="secondary">Mr. Johnson</Badge>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                    <BookOpen class="w-5 h-5 text-white" />
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Science</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Class 10B - Lab 101</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">9:30 - 10:30 AM</div>
                                <Badge variant="secondary">Dr. Smith</Badge>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                    <BookOpen class="w-5 h-5 text-white" />
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">English</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Class 9A - Room 105</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">11:00 - 12:00 PM</div>
                                <Badge variant="secondary">Ms. Davis</Badge>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
