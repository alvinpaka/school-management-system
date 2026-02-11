<script setup>
import { Head, Link } from '@inertiajs/vue3';
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
    BookOpen,
    Users,
    Grid3x3,
    Edit
} from 'lucide-vue-next';

const props = defineProps({
    academicClass: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <Head title="Class Details" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('classes.index')">
                    <Button variant="ghost" size="sm">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Back to Classes
                    </Button>
                </Link>
                <span class="text-gray-400">|</span>
                <span>Class Details</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Class Information Card -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <BookOpen class="w-5 h-5" />
                        <span>{{ academicClass.name }}</span>
                    </CardTitle>
                    <CardDescription>
                        Class information and section details
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Information</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <BookOpen class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Class Name:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">{{ academicClass.name }}</p>
                                
                                <div class="flex items-center space-x-2">
                                    <Grid3x3 class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Class Code:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">{{ academicClass.code }}</p>
                            </div>
                        </div>

                        <!-- Sections Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Sections</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <Users class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Total Sections:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    {{ academicClass.sections?.length || 0 }} sections
                                </p>
                                
                                <div v-if="academicClass.sections && academicClass.sections.length > 0" class="mt-4">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Section List:</span>
                                    <div class="mt-2 space-y-2">
                                        <div 
                                            v-for="section in academicClass.sections" 
                                            :key="section.id"
                                            class="flex items-center space-x-2 ml-6"
                                        >
                                            <Badge variant="secondary">{{ section.name }}</Badge>
                                        </div>
                                    </div>
                                </div>
                                
                                <div v-else class="mt-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">No sections assigned to this class</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                        <Link :href="route('classes.index')">
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
                                    <Link :href="route('classes.edit', academicClass.id)" class="flex items-center">
                                        <Edit class="w-4 h-4 mr-2" />
                                        Edit Class
                                    </Link>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
