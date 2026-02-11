<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { 
    ArrowLeft,
    Save,
    Calendar,
    Clock,
    Award,
    BookOpen
} from 'lucide-vue-next';

defineProps({
    subjects: Array
});

const form = useForm({
    name: '',
    subject_id: '',
    date: '',
    start_time: '',
    end_time: '',
    max_marks: 100
});

const submit = () => {
    form.post(route('exams.store'));
};
</script>

<template>
    <Head title="Schedule Exam" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('exams.index')">
                    <Button variant="ghost" size="sm">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Back to Exams
                    </Button>
                </Link>
                <span class="text-gray-400">|</span>
                <span>Schedule New Exam</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Form Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Exam Information</CardTitle>
                    <CardDescription>Enter details for the new exam</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Basic Information</h3>
                                
                                <div>
                                    <Label for="name">Exam Name</Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="e.g., Mid-Term Mathematics"
                                        required
                                        autofocus
                                    />
                                    <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.name }}
                                    </div>
                                </div>

                                <div>
                                    <Label for="subject_id">Subject</Label>
                                    <select
                                        id="subject_id"
                                        v-model="form.subject_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-lg px-3 py-2"
                                        required
                                    >
                                        <option value="">Select a subject</option>
                                        <option v-for="subject in subjects" :key="subject.id" :value="subject.id">
                                            {{ subject.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.subject_id" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.subject_id }}
                                    </div>
                                </div>

                                <div>
                                    <Label for="max_marks">Maximum Marks</Label>
                                    <Input
                                        id="max_marks"
                                        v-model="form.max_marks"
                                        type="number"
                                        class="mt-1 block w-full"
                                        min="1"
                                        required
                                    />
                                    <div v-if="form.errors.max_marks" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.max_marks }}
                                    </div>
                                </div>
                            </div>

                            <!-- Schedule Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Schedule Information</h3>
                                
                                <div>
                                    <Label for="date">Exam Date</Label>
                                    <Input
                                        id="date"
                                        v-model="form.date"
                                        type="date"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                    <div v-if="form.errors.date" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.date }}
                                    </div>
                                </div>

                                <div>
                                    <Label for="start_time">Start Time</Label>
                                    <Input
                                        id="start_time"
                                        v-model="form.start_time"
                                        type="time"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                    <div v-if="form.errors.start_time" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.start_time }}
                                    </div>
                                </div>

                                <div>
                                    <Label for="end_time">End Time</Label>
                                    <Input
                                        id="end_time"
                                        v-model="form.end_time"
                                        type="time"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                    <div v-if="form.errors.end_time" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.end_time }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <Link :href="route('exams.index')">
                                <Button variant="outline" type="button">
                                    Cancel
                                </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                <Save class="w-4 h-4 mr-2" />
                                {{ form.processing ? 'Scheduling...' : 'Schedule Exam' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
