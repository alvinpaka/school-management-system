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
    <Head title="Edit Class | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('classes.index')">
                    <Button variant="ghost" size="sm" class="gap-2 hover:bg-terracotta/10 text-warm-text text-dark-text">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Back to Classes
                    </Button>
                </Link>
                <span class="text-warm-muted text-dark-muted">|</span>
                <span class="font-semibold text-warm-text text-dark-text">Edit Class</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 animate-fade-in-up">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter mb-2">Edit Class</h1>
                    <p class="text-warm-muted text-dark-muted font-medium">
                        Update class details and sections
                    </p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card-warm">
                <div class="p-6 border-b border-terracotta/20">
                    <h3 class="text-lg font-black text-warm-text text-dark-text">Edit Class Information</h3>
                    <p class="text-sm text-warm-muted text-dark-muted mt-1">
                        Update the class details and sections.
                    </p>
                </div>
                <div class="p-6">
                    <form @submit.prevent="form.put(route('classes.update', props.academicClass.id))" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-black text-warm-text text-dark-text flex items-center gap-2">
                                    <BookOpen class="w-5 h-5 text-terracotta" />
                                    Basic Information
                                </h3>
                                
                                <div class="space-y-2">
                                    <Label for="name" class="text-warm-text text-dark-text">Class Name</Label>
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
                                    <Label for="code" class="text-warm-text text-dark-text">Class Code</Label>
                                    <Input
                                        id="code"
                                        v-model="form.code"
                                        type="text"
                                        class="mt-1 block w-full border-terracotta/20 focus:ring-terracotta/30"
                                        required
                                        placeholder="e.g., 10A, 9B"
                                    />
                                    <div v-if="form.errors.code" class="text-destructive text-sm mt-1">
                                        {{ form.errors.code }}
                                    </div>
                                </div>
                            </div>

                            <!-- Sections -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-black text-warm-text text-dark-text flex items-center gap-2">
                                    <Grid3x3 class="w-5 h-5 text-terracotta" />
                                    Sections
                                </h3>
                                
                                <div class="space-y-3">
                                    <div v-for="(section, index) in form.sections" :key="index" class="flex items-center space-x-2">
                                        <Input
                                            v-model="form.sections[index]"
                                            type="text"
                                            class="flex-1 border-terracotta/20 focus:ring-terracotta/30"
                                            :placeholder="`Section ${index + 1} name`"
                                        />
                                        <Button
                                            type="button"
                                            variant="outline"
                                            size="sm"
                                            @click="removeSection(index)"
                                            class="text-destructive hover:text-destructive border-terracotta/20"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </Button>
                                    </div>
                                    
                                    <Button
                                        type="button"
                                        variant="outline"
                                        @click="addSection"
                                        class="w-full border-terracotta/20"
                                    >
                                        <Plus class="w-4 h-4 mr-2" />
                                        Add Section
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-terracotta/20">
                            <Link :href="route('classes.index')">
                                <Button variant="outline" type="button" class="border-terracotta/20">
                                    Cancel
                                </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing" class="accent-terracotta text-white">
                                <Save class="w-4 h-4 mr-2" />
                                {{ form.processing ? 'Updating...' : 'Update Class' }}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Sidebar>
</template>