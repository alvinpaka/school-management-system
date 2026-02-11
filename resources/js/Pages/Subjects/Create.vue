<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { 
    ArrowLeft,
    Save,
    BookOpen,
    FileText
} from 'lucide-vue-next';

const form = useForm({
    name: '',
    code: '',
    type: 'theory',
});

const submit = () => {
    form.post(route('subjects.store'));
};
</script>

<template>
    <Head title="Add Subject" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Button variant="ghost" size="sm" :href="route('subjects.index')">
                    <ArrowLeft class="w-4 h-4 mr-2" />
                    Back to Subjects
                </Button>
                <span class="text-gray-400">|</span>
                <span>Add New Subject</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Form Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Subject Information</CardTitle>
                    <CardDescription>Enter the details for the new subject</CardDescription>
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
                                        placeholder="e.g., Mathematics"
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
                                        placeholder="e.g., MATH"
                                        required
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
                                        <option value="theory">Theory</option>
                                        <option value="practical">Practical</option>
                                        <option value="elective">Elective</option>
                                    </select>
                                    <div v-if="form.errors.type" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.type }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <Button variant="outline" type="button" :href="route('subjects.index')">
                                Cancel
                            </Button>
                            <Button type="submit" :disabled="form.processing">
                                <Save class="w-4 h-4 mr-2" />
                                {{ form.processing ? 'Creating...' : 'Create Subject' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
