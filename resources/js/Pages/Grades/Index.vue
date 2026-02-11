<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
    Award,
    TrendingUp,
    Users,
    BookOpen,
    Calendar,
    CheckCircle
} from 'lucide-vue-next';

defineProps({
    grades: Array,
    stats: Object
});
</script>

<template>
    <Head title="Grades Management" />

    <Sidebar>
        <template #header-title>
            Grades Management
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Grades Overview</h2>
                        <p class="text-gray-600 dark:text-gray-400">View and manage student grades</p>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                                <Award class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Average Grade</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats?.average_grade || 'N/A' }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                <TrendingUp class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pass Rate</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats?.pass_rate || 'N/A' }}%</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                                <Users class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Students</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats?.total_students || 0 }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center mr-4">
                                <BookOpen class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Exams</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats?.total_exams || 0 }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Recent Grades -->
            <Card>
                <CardHeader>
                    <CardTitle>Recent Grades</CardTitle>
                    <CardDescription>Latest grade entries across all classes</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="text-center py-12">
                        <Award class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Grades Module</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Comprehensive grade management system</p>
                        <div class="space-y-2 text-left max-w-md mx-auto">
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <CheckCircle class="w-4 h-4 mr-2 text-green-500" />
                                Grade entry and management
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <CheckCircle class="w-4 h-4 mr-2 text-green-500" />
                                Performance analytics
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <CheckCircle class="w-4 h-4 mr-2 text-green-500" />
                                Grade reports and transcripts
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <CheckCircle class="w-4 h-4 mr-2 text-green-500" />
                                Class performance tracking
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
