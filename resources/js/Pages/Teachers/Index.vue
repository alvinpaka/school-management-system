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
    Mail,
    Phone
} from 'lucide-vue-next';

defineProps({
    teachers: Array
});

const deleteTeacher = (id) => {
    if (confirm('Are you sure you want to delete this teacher?')) {
        router.delete(route('teachers.destroy', id));
    }
};
</script>

<template>
    <Head title="Teachers" />

    <Sidebar>
        <template #header-title>
            Teachers Management
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Teachers</h2>
                        <p class="text-gray-600 dark:text-gray-400">Manage all teachers in the system</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <Button variant="outline" size="sm">
                            <Download class="w-4 h-4 mr-2" />
                            Export
                        </Button>
                        <Link :href="route('teachers.create')">
                            <Button>
                                <Plus class="w-4 h-4 mr-2" />
                                Add Teacher
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Teachers Card -->
            <Card>
                <CardHeader>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <CardTitle>All Teachers</CardTitle>
                            <CardDescription>{{ teachers.length }} total teachers</CardDescription>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                                <input
                                    type="text"
                                    placeholder="Search teachers..."
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
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Employee ID</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Name</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Contact</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Specialization</th>
                                    <th class="text-right py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="teacher in teachers" :key="teacher.id" class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="py-3 px-4">
                                        <span class="font-mono text-sm text-gray-600 dark:text-gray-400">{{ teacher.employee_id }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-white text-sm font-medium">{{ teacher.user.name.charAt(0).toUpperCase() }}</span>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ teacher.user.name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ teacher.user.email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <Mail class="w-3 h-3 mr-1" />
                                                {{ teacher.user.email }}
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <Phone class="w-3 h-3 mr-1" />
                                                {{ teacher.phone || 'N/A' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <Badge variant="secondary">
                                            {{ teacher.specialization || 'General' }}
                                        </Badge>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-end space-x-2">
                                            <Link :href="route('teachers.show', teacher.id)">
                                                <Button variant="ghost" size="sm">
                                                    <Eye class="w-4 h-4" />
                                                </Button>
                                            </Link>
                                            <Link :href="route('teachers.edit', teacher.id)">
                                                <Button variant="ghost" size="sm">
                                                    <Edit class="w-4 h-4" />
                                                </Button>
                                            </Link>
                                            <Button 
                                                variant="ghost" 
                                                size="sm" 
                                                @click="deleteTeacher(teacher.id)"
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
