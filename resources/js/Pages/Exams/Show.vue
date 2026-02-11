<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { 
    ArrowLeft,
    Calendar,
    Clock,
    Award,
    BookOpen,
    User
} from 'lucide-vue-next';

const props = defineProps({
    exam: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <Head title="Exam Details" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Button variant="ghost" size="sm" :href="route('exams.index')">
                    <ArrowLeft class="w-4 h-4 mr-2" />
                    Back to Exams
                </Button>
                <span class="text-gray-400">|</span>
                <span>Exam Details</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Exam Information Card -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <Calendar class="w-5 h-5" />
                        <span>{{ exam.name }}</span>
                    </CardTitle>
                    <CardDescription>
                        Exam information and schedule details
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
                                    <span class="text-sm font-medium">Subject:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    {{ exam.subject?.name || 'Not assigned' }}
                                </p>
                                
                                <div class="flex items-center space-x-2">
                                    <Award class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Maximum Marks:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">{{ exam.max_marks }}</p>
                            </div>
                        </div>

                        <!-- Schedule Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Schedule Information</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <Calendar class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Exam Date:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    {{ exam.date || 'Not scheduled' }}
                                </p>
                                
                                <div class="flex items-center space-x-2">
                                    <Clock class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Start Time:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    {{ exam.start_time || 'Not specified' }}
                                </p>
                                
                                <div class="flex items-center space-x-2">
                                    <Clock class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">End Time:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    {{ exam.end_time || 'Not specified' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                        <Button variant="outline" :href="route('exams.index')">
                            <ArrowLeft class="w-4 h-4 mr-2" />
                            Back to List
                        </Button>
                        <Link :href="route('exams.edit', exam.id)">
                            <Button>Edit Exam</Button>
                        </Link>
                        <Link :href="route('exams.grades.enter', exam.id)">
                            <Button variant="secondary">
                                <Award class="w-4 h-4 mr-2" />
                                Enter Grades
                            </Button>
                        </Link>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
