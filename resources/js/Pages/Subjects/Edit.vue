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
    <Head title="Edit Subject" />

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
                <span>Edit Subject</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Form Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Edit Subject Information</CardTitle>
                    <CardDescription>
                        Update the subject details and type.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Basic Information</h3>
                                
                                <div>
                                    <Label for="name">Subject Name</Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        required
                                        autofocus
                                    />
                                    <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.name }}
                                    </div>
                                </div>

                                <div>
                                    <Label for="code">Subject Code</Label>
                                    <Input
                                        id="code"
                                        v-model="form.code"
                                        type="text"
                                        class="mt-1 block w-full"
                                        required
                                        placeholder="e.g., MATH, PHYS, CHEM"
                                    />
                                    <div v-if="form.errors.code" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.code }}
                                    </div>
                                </div>
                            </div>

                            <!-- Subject Type -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Subject Type</h3>
                                
                                <div>
                                    <Label for="type">Type</Label>
                                    <select
                                        id="type"
                                        v-model="form.type"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-lg px-3 py-2"
                                        required
                                    >
                                        <option value="">Select a type</option>
                                        <option value="theory">Theory</option>
                                        <option value="practical">Practical</option>
                                        <option value="elective">Elective</option>
                                    </select>
                                    <div v-if="form.errors.type" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.type }}
                                    </div>
                                </div>

                                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
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
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <Link :href="route('subjects.index')">
                                <Button variant="outline" type="button">
                                    Cancel
                                </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                <Save class="w-4 h-4 mr-2" />
                                {{ form.processing ? 'Updating...' : 'Update Subject' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
