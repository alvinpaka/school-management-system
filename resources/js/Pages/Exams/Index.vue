<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
    Plus,
    Edit,
    Trash2,
    Search,
    Filter,
    Download,
    Eye,
    Calendar,
    Clock,
    FileText
} from 'lucide-vue-next';

defineProps({
    exams: Array
});

const deleteExam = (id) => {
    if (confirm('Are you sure you want to delete this exam?')) {
        router.delete(route('exams.destroy', id));
    }
};
</script>

<template>
    <Head title="Exam Management" />

    <Sidebar>
        <template #header-title>
            Exam Management
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Exam Schedule</h2>
                        <p class="text-gray-600 dark:text-gray-400">Manage and track all examinations</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <Button variant="outline" size="sm">
                            <Download class="w-4 h-4 mr-2" />
                            Export Schedule
                        </Button>
                        <Link :href="route('exams.create')">
                            <Button>
                                <Plus class="w-4 h-4 mr-2" />
                                Schedule Exam
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Exams Card -->
            <Card>
                <CardHeader>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <CardTitle>All Exams</CardTitle>
                            <CardDescription>{{ exams.length }} scheduled exams</CardDescription>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                                <input
                                    type="text"
                                    placeholder="Search exams..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                />
                            </div>
                            <Button variant="outline" size="sm">
                                <Filter class="w-4 h-4" />
                            </Button>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Exam Title</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Class</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Date</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Duration</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Status</th>
                                    <th class="text-right py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="exam in exams" :key="exam.id" class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                                                <FileText class="w-4 h-4 text-white" />
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ exam.title }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ exam.subject?.name || 'General' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <Badge variant="secondary">
                                            {{ exam.academic_class?.name }} - {{ exam.section?.name }}
                                        </Badge>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <Calendar class="w-4 h-4 mr-1" />
                                            {{ new Date(exam.exam_date).toLocaleDateString() }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <Clock class="w-4 h-4 mr-1" />
                                            {{ exam.duration }} minutes
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <Badge :class="exam.status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : exam.status === 'ongoing' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'">
                                            {{ exam.status }}
                                        </Badge>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-end space-x-2">
                                            <Link :href="route('exams.show', exam.id)">
                                                <Button variant="ghost" size="sm">
                                                    <Eye class="w-4 h-4" />
                                                </Button>
                                            </Link>
                                            <Link :href="route('exams.edit', exam.id)">
                                                <Button variant="ghost" size="sm">
                                                    <Edit class="w-4 h-4" />
                                                </Button>
                                            </Link>
                                            <Button 
                                                variant="ghost" 
                                                size="sm" 
                                                @click="deleteExam(exam.id)"
                                                class="text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20"
                                            >
                                                <Trash2 class="w-4 h-4" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>

                                                                
