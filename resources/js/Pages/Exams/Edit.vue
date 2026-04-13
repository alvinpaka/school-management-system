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

const props = defineProps({
    exam: Object,
    subjects: Array
});

const form = useForm({
    name: props.exam.name,
    subject_id: props.exam.subject_id,
    date: props.exam.date,
    start_time: props.exam.start_time,
    end_time: props.exam.end_time,
    max_marks: props.exam.max_marks
});

const submit = () => {
    form.put(route('exams.update', props.exam.id));
};
</script>

<template>
    <Head title="Edit Exam | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('exams.index')">
                    <Button variant="ghost" size="sm" class="gap-2 hover:bg-terracotta/10 text-warm-text dark:text-dark-text">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Back to Exams
                    </Button>
                </Link>
                <span class="text-warm-muted dark:text-dark-muted">|</span>
                <span class="font-semibold text-warm-text dark:text-dark-text">Edit Exam</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 animate-fade-in-up">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-warm-text dark:text-dark-text tracking-tighter mb-2">Edit Exam</h1>
                    <p class="text-warm-muted dark:text-dark-muted font-medium">
                        Update exam details and schedule
                    </p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card-warm">
                <div class="p-6 border-b border-terracotta/20">
                    <h3 class="text-lg font-black text-warm-text dark:text-dark-text">Edit Exam Information</h3>
                    <p class="text-sm text-warm-muted dark:text-dark-muted mt-1">
                        Update the exam details and schedule.
                    </p>
                </div>
                <div class="p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-black text-warm-text dark:text-dark-text flex items-center gap-2">
                                    <Award class="w-5 h-5 text-terracotta" />
                                    Basic Information
                                </h3>
                                
                                <div class="space-y-2">
                                    <Label for="name" class="text-warm-text dark:text-dark-text">Exam Name</Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        class="mt-1 block w-full border-terracotta/20 focus:ring-terracotta/30"
                                        required
                                        autofocus
                                    />
                                    <div v-if="form.errors.name" class="text-destructive text-sm mt-1">
                                        {{ form.errors.name }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <Label for="subject_id" class="text-warm-text dark:text-dark-text">Subject</Label>
                                    <select
                                        id="subject_id"
                                        v-model="form.subject_id"
                                        class="mt-1 block w-full border border-terracotta/20 rounded-lg px-3 py-2 bg-white dark:bg-dark-bg text-warm-text dark:text-dark-text focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta transition-colors"
                                        required
                                    >
                                        <option value="">Select a subject</option>
                                        <option v-for="subject in props.subjects" :key="subject.id" :value="subject.id">
                                            {{ subject.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.subject_id" class="text-destructive text-sm mt-1">
                                        {{ form.errors.subject_id }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <Label for="max_marks" class="text-warm-text dark:text-dark-text">Maximum Marks</Label>
                                    <Input
                                        id="max_marks"
                                        v-model="form.max_marks"
                                        type="number"
                                        class="mt-1 block w-full border-terracotta/20 focus:ring-terracotta/30"
                                        min="1"
                                        required
                                    />
                                    <div v-if="form.errors.max_marks" class="text-destructive text-sm mt-1">
                                        {{ form.errors.max_marks }}
                                    </div>
                                </div>
                            </div>

                            <!-- Schedule Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-black text-warm-text dark:text-dark-text flex items-center gap-2">
                                    <Calendar class="w-5 h-5 text-terracotta" />
                                    Schedule Information
                                </h3>
                                
                                <div class="space-y-2">
                                    <Label for="date" class="text-warm-text dark:text-dark-text">Exam Date</Label>
                                    <Input
                                        id="date"
                                        v-model="form.date"
                                        type="date"
                                        class="mt-1 block w-full border-terracotta/20 focus:ring-terracotta/30"
                                        required
                                    />
                                    <div v-if="form.errors.date" class="text-destructive text-sm mt-1">
                                        {{ form.errors.date }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <Label for="start_time" class="text-warm-text dark:text-dark-text">Start Time</Label>
                                    <Input
                                        id="start_time"
                                        v-model="form.start_time"
                                        type="time"
                                        class="mt-1 block w-full border-terracotta/20 focus:ring-terracotta/30"
                                        required
                                    />
                                    <div v-if="form.errors.start_time" class="text-destructive text-sm mt-1">
                                        {{ form.errors.start_time }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <Label for="end_time" class="text-warm-text dark:text-dark-text">End Time</Label>
                                    <Input
                                        id="end_time"
                                        v-model="form.end_time"
                                        type="time"
                                        class="mt-1 block w-full border-terracotta/20 focus:ring-terracotta/30"
                                        required
                                    />
                                    <div v-if="form.errors.end_time" class="text-destructive text-sm mt-1">
                                        {{ form.errors.end_time }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-terracotta/20">
                            <Link :href="route('exams.index')">
                                <Button variant="outline" type="button" class="border-terracotta/20">
                                    Cancel
                                </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing" class="accent-terracotta text-white">
                                <Save class="w-4 h-4 mr-2" />
                                {{ form.processing ? 'Updating...' : 'Update Exam' }}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Sidebar>
</template>