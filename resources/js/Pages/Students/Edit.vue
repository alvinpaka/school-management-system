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
    User,
    Mail,
    GraduationCap,
    Calendar,
    MapPin
} from 'lucide-vue-next';

const props = defineProps({
    student: Object,
    classes: Array,
    sections: Array
});

const form = useForm({
    name: props.student.user.name,
    email: props.student.user.email,
    password: '',
    academic_class_id: props.student.academic_class_id,
    section_id: props.student.section_id,
    admission_number: props.student.admission_number,
    date_of_birth: props.student.date_of_birth,
});

const submit = () => {
    form.put(route('students.update', props.student.id));
};
</script>

<template>
    <Head title="Edit Student" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('students.index')">
                    <Button variant="ghost" size="sm">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Back to Students
                    </Button>
                </Link>
                <span class="text-gray-400">|</span>
                <span>Edit Student</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Form Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Edit Student Information</CardTitle>
                    <CardDescription>
                        Update the student's personal and academic information.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Personal Information</h3>
                                
                                <div>
                                    <Label for="name">Full Name</Label>
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
                                    <Label for="email">Email Address</Label>
                                    <Input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        required
                                        autocomplete="username"
                                    />
                                    <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.email }}
                                    </div>
                                </div>

                                <div>
                                    <Label for="password">New Password (leave blank to keep current)</Label>
                                    <Input
                                        id="password"
                                        v-model="form.password"
                                        type="password"
                                        class="mt-1 block w-full"
                                        placeholder="Leave empty to keep current password"
                                        autocomplete="new-password"
                                    />
                                    <div v-if="form.errors.password" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.password }}
                                    </div>
                                </div>
                            </div>

                            <!-- Academic Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Academic Information</h3>
                                
                                <div>
                                    <Label for="admission_number">Admission Number</Label>
                                    <Input
                                        id="admission_number"
                                        v-model="form.admission_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                    <div v-if="form.errors.admission_number" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.admission_number }}
                                    </div>
                                </div>

                                <div>
                                    <Label for="academic_class_id">Class</Label>
                                    <select
                                        id="academic_class_id"
                                        v-model="form.academic_class_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-lg px-3 py-2"
                                        required
                                    >
                                        <option value="">Select a class</option>
                                        <option v-for="classItem in classes" :key="classItem.id" :value="classItem.id">
                                            {{ classItem.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.academic_class_id" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.academic_class_id }}
                                    </div>
                                </div>

                                <div>
                                    <Label for="section_id">Section</Label>
                                    <select
                                        id="section_id"
                                        v-model="form.section_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-lg px-3 py-2"
                                        required
                                    >
                                        <option value="">Select a section</option>
                                        <option v-for="section in sections" :key="section.id" :value="section.id">
                                            {{ section.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.section_id" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.section_id }}
                                    </div>
                                </div>

                                <div>
                                    <Label for="date_of_birth">Date of Birth</Label>
                                    <Input
                                        id="date_of_birth"
                                        v-model="form.date_of_birth"
                                        type="date"
                                        class="mt-1 block w-full"
                                    />
                                    <div v-if="form.errors.date_of_birth" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.date_of_birth }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <Link :href="route('students.index')">
                                <Button variant="outline" type="button">
                                    Cancel
                                </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                <Save class="w-4 h-4 mr-2" />
                                {{ form.processing ? 'Updating...' : 'Update Student' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
