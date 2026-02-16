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
    Users,
    Calendar
} from 'lucide-vue-next';

const { classes, filters } = defineProps({
    classes: Object,
    filters: Object
});

const searchQuery = ref(filters?.search || '');

const deleteClass = (id) => {
    if (confirm('Are you sure you want to delete this class? Sections will also be removed.')) {
        router.delete(route('classes.destroy', id));
    }
};

const handlePageChange = (page) => {
    const params = { page };
    if (searchQuery.value) {
        params.search = searchQuery.value;
    }
    router.get(route('classes.index'), params, { preserveState: true });
};

// Watch for search query changes
watch(searchQuery, (newValue) => {
    router.get(route('classes.index'), { 
        search: newValue, 
        page: 1 
    }, { preserveState: true });
}, { debounce: 300 });
</script>

<template>
    <Head title="Academic Classes" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Calendar class="w-5 h-5" />
                <span>Classes</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Academic Classes</h2>
                        <p class="text-gray-600 dark:text-gray-400">Manage all academic classes and sections</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <Button variant="outline" size="sm">
                            <Download class="w-4 h-4 mr-2" />
                            Export
                        </Button>
                        <Link :href="route('classes.create')">
                            <Button>
                                <Plus class="w-4 h-4 mr-2" />
                                Add Class
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Classes Card -->
            <Card>
                <CardHeader>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <CardTitle>All Classes</CardTitle>
                            <CardDescription>{{ classes.total }} total classes</CardDescription>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                                <input
                                    type="text"
                                    v-model="searchQuery"
                                    placeholder="Search classes..."
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
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Class Name</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Sections</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Class Teacher</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Students</th>
                                    <th class="text-right py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="classItem in classes.data" :key="classItem.id" class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center mr-3">
                                                <span class="text-white text-sm font-medium">{{ classItem.name.charAt(0).toUpperCase() }}</span>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ classItem.name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">Grade {{ classItem.grade_level }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-wrap gap-1">
                                            <Badge v-for="section in classItem.sections" :key="section.id" variant="secondary" class="text-xs">
                                                {{ section.name }}
                                            </Badge>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ classItem.class_teacher?.user?.name || 'Not Assigned' }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <Users class="w-4 h-4 mr-1" />
                                            {{ classItem.total_students || 0 }} students
                                        </div>
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
                                                        <Link :href="route('classes.show', classItem.id)" class="flex items-center">
                                                            <Eye class="w-4 h-4 mr-2" />
                                                            View Details
                                                        </Link>
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem as-child>
                                                        <Link :href="route('classes.edit', classItem.id)" class="flex items-center">
                                                            <Edit class="w-4 h-4 mr-2" />
                                                            Edit Class
                                                        </Link>
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem @click="deleteClass(classItem.id)" class="flex items-center text-red-600">
                                                        <Trash2 class="w-4 h-4 mr-2" />
                                                        Delete Class
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
                        :data="classes" 
                        @page-change="handlePageChange"
                    />
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
