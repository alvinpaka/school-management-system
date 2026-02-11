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
    Calendar,
    MapPin,
    Edit,
    Trash2,
    GraduationCap
} from 'lucide-vue-next';

const props = defineProps({
    student: {
        type: Object,
        required: true
    }
});

const deleteStudent = () => {
    if (confirm('Are you sure you want to delete this student?')) {
        router.delete(route('students.destroy', props.student.id));
    }
};
</script>

<template>
    <Head title="Student Details" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
            <Link :href="route('students.index')">
                <Button variant="ghost" size="sm">
                    <ArrowLeft class="w-4 h-4 mr-2" />
                    Back to Students
                </Button>
            </Link>
                <span class="text-gray-400">|</span>
                <span>Student Details</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Student Information Card -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <User class="w-5 h-5" />
                        <span>{{ student.user.name }}</span>
                    </CardTitle>
                    <CardDescription>
                        Student information and academic details
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
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">{{ student.user.email }}</p>
                                
                                <div class="flex items-center space-x-2">
                                    <Calendar class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Date of Birth:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    {{ student.date_of_birth || 'Not specified' }}
                                </p>
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Academic Information</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <GraduationCap class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Admission Number:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">{{ student.admission_number }}</p>
                                
                                <div class="flex items-center space-x-2">
                                    <MapPin class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Class:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    {{ student.academic_class?.name || 'Not assigned' }}
                                </p>
                                
                                <div class="flex items-center space-x-2">
                                    <MapPin class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Section:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    {{ student.section?.name || 'Not assigned' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                        <Link :href="route('students.index')">
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
                                    <Link :href="route('students.edit', student.id)" class="flex items-center">
                                        <Edit class="w-4 h-4 mr-2" />
                                        Edit Student
                                    </Link>
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="deleteStudent" class="flex items-center text-red-600">
                                    <Trash2 class="w-4 h-4 mr-2" />
                                    Delete Student
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
