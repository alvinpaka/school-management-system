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
    Phone,
    Briefcase
} from 'lucide-vue-next';

const props = defineProps({
    teacher: Object
});

const form = useForm({
    name: props.teacher.user.name,
    email: props.teacher.user.email,
    password: '',
    employee_id: props.teacher.employee_id,
    phone: props.teacher.phone,
    specialization: props.teacher.specialization,
});

const submit = () => {
    form.put(route('teachers.update', props.teacher.id));
};
</script>

<template>
    <Head title="Edit Teacher" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('teachers.index')">
                    <Button variant="ghost" size="sm">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Back to Teachers
                    </Button>
                </Link>
                <span class="text-gray-400">|</span>
                <span>Edit Teacher</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Form Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Edit Teacher Information</CardTitle>
                    <CardDescription>
                        Update the teacher's personal and professional information.
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

                            <!-- Professional Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Professional Information</h3>
                                
                                <div>
                                    <Label for="employee_id">Employee ID</Label>
                                    <Input
                                        id="employee_id"
                                        v-model="form.employee_id"
                                        type="text"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                    <div v-if="form.errors.employee_id" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.employee_id }}
                                    </div>
                                </div>

                                <div>
                                    <Label for="phone">Phone Number</Label>
                                    <Input
                                        id="phone"
                                        v-model="form.phone"
                                        type="tel"
                                        class="mt-1 block w-full"
                                        placeholder="e.g., +1234567890"
                                    />
                                    <div v-if="form.errors.phone" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.phone }}
                                    </div>
                                </div>

                                <div>
                                    <Label for="specialization">Specialization</Label>
                                    <Input
                                        id="specialization"
                                        v-model="form.specialization"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="e.g., Mathematics, Physics, English"
                                    />
                                    <div v-if="form.errors.specialization" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.specialization }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <Link :href="route('teachers.index')">
                                <Button variant="outline" type="button">
                                    Cancel
                                </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                <Save class="w-4 h-4 mr-2" />
                                {{ form.processing ? 'Updating...' : 'Update Teacher' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
