<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import Pagination from '@/components/ui/pagination.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
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
    FileText,
    Trophy
} from 'lucide-vue-next';

const { exams, filters } = defineProps({
    exams: Object,
    filters: Object
});

const searchQuery = ref(filters?.search || '');

const deleteExam = (id) => {
    if (confirm('Are you sure you want to delete this exam?')) {
        router.delete(route('exams.destroy', id));
    }
};

const handlePageChange = (page) => {
    const params = { page };
    if (searchQuery.value) {
        params.search = searchQuery.value;
    }
    router.get(route('exams.index'), params, { preserveState: true });
};

// Watch for search query changes
watch(searchQuery, (newValue) => {
    router.get(route('exams.index'), { 
        search: newValue, 
        page: 1 
    }, { preserveState: true });
}, { debounce: 300 });
</script>

<template>
    <Head title="Exam Management" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Trophy class="w-5 h-5" />
                <span>Exams</span>
            </div>
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
                            <CardDescription>{{ exams.total }} scheduled exams</CardDescription>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                                <input
                                    type="text"
                                    v-model="searchQuery"
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
                                <tr v-for="exam in exams.data" :key="exam.id" class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
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
                                        <div class="flex items-center justify-end">
                                            <DropdownMenu>
                                                <DropdownMenuTrigger as-child>
                                                    <Button variant="ghost" size="sm">
                                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                        </svg>
                                                    </Button>
                                                </DropdownMenuTrigger>
                                                <DropdownMenuContent align="end">
                                                    <DropdownMenuItem as-child>
                                                        <Link :href="route('exams.show', exam.id)" class="flex items-center">
                                                            <Eye class="w-4 h-4 mr-2" />
                                                            View Details
                                                        </Link>
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem as-child>
                                                        <Link :href="route('exams.edit', exam.id)" class="flex items-center">
                                                            <Edit class="w-4 h-4 mr-2" />
                                                            Edit Exam
                                                        </Link>
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem as-child>
                                                        <Link :href="route('exams.grades.enter', exam.id)" class="flex items-center">
                                                            <Trophy class="w-4 h-4 mr-2" />
                                                            Enter Grades
                                                        </Link>
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem @click="deleteExam(exam.id)" class="flex items-center text-red-600">
                                                        <Trash2 class="w-4 h-4 mr-2" />
                                                        Delete Exam
                                                    </DropdownMenuItem>
                                                </DropdownMenuContent>
                                            </DropdownMenu>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <Pagination 
                        :data="exams" 
                        @page-change="handlePageChange"
                    />
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>

                                                                
