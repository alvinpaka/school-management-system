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
    Edit
} from 'lucide-vue-next';

const props = defineProps({
    subject: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <Head title="Subject Details" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('subjects.index')">
                    <Button variant="ghost" size="sm">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Back to Subjects
                    </Button>
                </Link>
                <span class="text-gray-400">|</span>
                <span>Subject Details</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Subject Information Card -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <BookOpen class="w-5 h-5" />
                        <span>{{ subject.name }}</span>
                    </CardTitle>
                    <CardDescription>
                        Subject information and details
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
                                    <span class="text-sm font-medium">Subject Name:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">{{ subject.name }}</p>
                                
                                <div class="flex items-center space-x-2">
                                    <Tag class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Subject Code:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">{{ subject.code }}</p>
                            </div>
                        </div>

                        <!-- Subject Type -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Subject Type</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <FileText class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Type:</span>
                                </div>
                                <div class="ml-6">
                                    <Badge 
                                        :variant="subject.type === 'theory' ? 'default' : subject.type === 'practical' ? 'secondary' : 'outline'"
                                    >
                                        {{ subject.type.charAt(0).toUpperCase() + subject.type.slice(1) }}
                                    </Badge>
                                </div>
                                
                                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        <span v-if="subject.type === 'theory'">
                                            This is a theory-based subject focusing on conceptual learning and theoretical knowledge.
                                        </span>
                                        <span v-else-if="subject.type === 'practical'">
                                            This is a practical subject involving hands-on learning and practical applications.
                                        </span>
                                        <span v-else-if="subject.type === 'elective'">
                                            This is an elective subject that students can choose based on their interests.
                                        </span>
                                        <span v-else>
                                            Subject type information not available.
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                        <Link :href="route('subjects.index')">
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
                                    <Link :href="route('subjects.edit', subject.id)" class="flex items-center">
                                        <Edit class="w-4 h-4 mr-2" />
                                        Edit Subject
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
