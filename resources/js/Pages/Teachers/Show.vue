<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { 
    ArrowLeft,
    User,
    Mail,
    Phone,
    Briefcase,
    Edit,
    Trash2
} from 'lucide-vue-next';

const props = defineProps({
    teacher: {
        type: Object,
        required: true
    }
});

const deleteTeacher = () => {
    if (confirm('Are you sure you want to delete this teacher?')) {
        router.delete(route('teachers.destroy', props.teacher.id));
    }
};
</script>

<template>
    <Head title="Teacher Details" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('teachers.index')">
                    <Button variant="ghost" size="sm">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Back to Teachers
                    </Button>
                </Link>
                <span class="text-gray-400">|</span>
                <span>Teacher Details</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Teacher Information Card -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <User class="w-5 h-5" />
                        <span>{{ teacher.user.name }}</span>
                    </CardTitle>
                    <CardDescription>
                        Teacher information and professional details
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Personal Information</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <Mail class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Email:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">{{ teacher.user.email }}</p>
                                
                                <div class="flex items-center space-x-2">
                                    <Phone class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Phone:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    {{ teacher.phone || 'Not specified' }}
                                </p>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Professional Information</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <Briefcase class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Employee ID:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">{{ teacher.employee_id }}</p>
                                
                                <div class="flex items-center space-x-2">
                                    <Briefcase class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Specialization:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    {{ teacher.specialization || 'Not specified' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                        <Link :href="route('teachers.index')">
                            <Button variant="outline">
                                <ArrowLeft class="w-4 h-4 mr-2" />
                                Back to List
                            </Button>
                        </Link>
                        
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button>
                                    Actions
                                    <svg class="w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end">
                                <DropdownMenuItem as-child>
                                    <Link :href="route('teachers.edit', teacher.id)" class="flex items-center">
                                        <Edit class="w-4 h-4 mr-2" />
                                        Edit Teacher
                                    </Link>
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="deleteTeacher" class="flex items-center text-red-600">
                                    <Trash2 class="w-4 h-4 mr-2" />
                                    Delete Teacher
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
