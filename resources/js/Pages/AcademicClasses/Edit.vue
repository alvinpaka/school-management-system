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
    Plus,
    Trash2,
    Grid3x3
} from 'lucide-vue-next';

const props = defineProps({
    academicClass: Object
});

const form = useForm({
    name: props.academicClass.name,
    code: props.academicClass.code,
    sections: props.academicClass.sections.map(s => s.name)
});

if (form.sections.length === 0) {
    form.sections.push('');
}

const addSection = () => {
    form.sections.push('');
};

const removeSection = (index) => {
    form.sections.splice(index, 1);
};

const submit = () => {
    form.put(route('classes.update', props.academicClass.id));
};
</script>

<template>
    <Head title="Edit Class" />

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
                <span>Edit Class</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Form Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Edit Class Information</CardTitle>
                    <CardDescription>
                        Update the class details and sections.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="form.put(route('classes.update', props.academicClass.id))" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Basic Information</h3>
                                
                                <div>
                                    <Label for="name">Class Name</Label>
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
                                    <Label for="code">Class Code</Label>
                                    <Input
                                        id="code"
                                        v-model="form.code"
                                        type="text"
                                        class="mt-1 block w-full"
                                        required
                                        placeholder="e.g., 10A, 9B"
                                    />
                                    <div v-if="form.errors.code" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.code }}
                                    </div>
                                </div>
                            </div>

                            <!-- Sections -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Sections</h3>
                                
                                <div class="space-y-3">
                                    <div v-for="(section, index) in form.sections" :key="index" class="flex items-center space-x-2">
                                        <Input
                                            v-model="form.sections[index]"
                                            type="text"
                                            class="flex-1"
                                            :placeholder="`Section ${index + 1} name`"
                                        />
                                        <Button
                                            type="button"
                                            variant="outline"
                                            size="sm"
                                            @click="removeSection(index)"
                                            class="text-red-600 hover:text-red-700"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </Button>
                                    </div>
                                    
                                    <Button
                                        type="button"
                                        variant="outline"
                                        @click="addSection"
                                        class="w-full"
                                    >
                                        <Plus class="w-4 h-4 mr-2" />
                                        Add Section
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <Link :href="route('classes.index')">
                                <Button variant="outline" type="button">
                                    Cancel
                                </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                <Save class="w-4 h-4 mr-2" />
                                {{ form.processing ? 'Updating...' : 'Update Class' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
