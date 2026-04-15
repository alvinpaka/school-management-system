<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { 
    ArrowLeft,
    Save,
    BookOpen,
    Tag,
    FileText
} from 'lucide-vue-next';

const props = defineProps({
    subject: Object
});

const form = useForm({
    name: props.subject.name,
    code: props.subject.code,
    type: props.subject.type,
});

const submit = () => {
    form.put(route('subjects.update', props.subject.id));
};
</script>

<template>
    <Head title="Edit Subject | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('subjects.index')">
                    <Button variant="ghost" size="sm" class="gap-2 hover:bg-terracotta/10 text-warm-text text-dark-text">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Back to Subjects
                    </Button>
                </Link>
                <span class="text-warm-muted text-dark-muted">|</span>
                <span class="font-semibold text-warm-text text-dark-text">Edit Subject</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 animate-fade-in-up">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter mb-2">Edit Subject</h1>
                    <p class="text-warm-muted text-dark-muted font-medium">
                        Update subject details and type
                    </p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card-warm">
                <div class="p-6 border-b border-terracotta/20">
                    <h3 class="text-lg font-black text-warm-text text-dark-text">Edit Subject Information</h3>
                    <p class="text-sm text-warm-muted text-dark-muted mt-1">
                        Update the subject details and type.
                    </p>
                </div>
                <div class="p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-black text-warm-text text-dark-text flex items-center gap-2">
                                    <BookOpen class="w-5 h-5 text-terracotta" />
                                    Basic Information
                                </h3>
                                
                                <div class="space-y-2">
                                    <Label for="name" class="text-warm-text text-dark-text">Subject Name</Label>
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
                                    <Label for="code" class="text-warm-text text-dark-text">Subject Code</Label>
                                    <Input
                                        id="code"
                                        v-model="form.code"
                                        type="text"
                                        class="mt-1 block w-full border-terracotta/20 focus:ring-terracotta/30"
                                        required
                                        placeholder="e.g., MATH, PHYS, CHEM"
                                    />
                                    <div v-if="form.errors.code" class="text-destructive text-sm mt-1">
                                        {{ form.errors.code }}
                                    </div>
                                </div>
                            </div>

                            <!-- Subject Type -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-black text-warm-text text-dark-text flex items-center gap-2">
                                    <Tag class="w-5 h-5 text-terracotta" />
                                    Subject Type
                                </h3>
                                
                                <div class="space-y-2">
                                    <Label for="type" class="text-warm-text text-dark-text">Type</Label>
                                    <select
                                        id="type"
                                        v-model="form.type"
                                        class="mt-1 block w-full border border-terracotta/20 rounded-lg px-3 py-2 bg-white bg-dark-bg text-warm-text text-dark-text focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta transition-colors"
                                        required
                                    >
                                        <option value="">Select a type</option>
                                        <option value="theory">Theory</option>
                                        <option value="practical">Practical</option>
                                        <option value="elective">Elective</option>
                                    </select>
                                    <div v-if="form.errors.type" class="text-destructive text-sm mt-1">
                                        {{ form.errors.type }}
                                    </div>
                                </div>

                                <div class="mt-4 p-4 bg-terracotta/5 border border-terracotta/20 rounded-lg">
                                    <p class="text-sm text-warm-muted text-dark-muted">
                                        <span v-if="form.type === 'theory'">
                                            Theory subjects focus on conceptual learning and theoretical knowledge.
                                        </span>
                                        <span v-else-if="form.type === 'practical'">
                                            Practical subjects involve hands-on learning and practical applications.
                                        </span>
                                        <span v-else-if="form.type === 'elective'">
                                            Elective subjects are optional courses that students can choose based on their interests.
                                        </span>
                                        <span v-else>
                                            Select a subject type to see its description.
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-terracotta/20">
                            <Link :href="route('subjects.index')">
                                <Button variant="outline" type="button" class="border-terracotta/20">
                                    Cancel
                                </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing" class="accent-terracotta text-white">
                                <Save class="w-4 h-4 mr-2" />
                                {{ form.processing ? 'Updating...' : 'Update Subject' }}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Sidebar>
</template>