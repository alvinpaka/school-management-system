<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    employee_id: '',
    phone: '',
    specialization: '',
});

const submit = () => {
    form.post(route('teachers.store'));
};
</script>

<template>
    <Head title="Add Teacher" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Add New Teacher
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="bg-white border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <InputLabel for="name" value="Name" />
                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="email" value="Email" />
                                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div>
                                <InputLabel for="password" value="Password" />
                                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <div>
                                <InputLabel for="employee_id" value="Employee ID" />
                                <TextInput id="employee_id" type="text" class="mt-1 block w-full font-mono" v-model="form.employee_id" required />
                                <InputError class="mt-2" :message="form.errors.employee_id" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="phone" value="Phone" />
                                    <TextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" />
                                    <InputError class="mt-2" :message="form.errors.phone" />
                                </div>

                                <div>
                                    <InputLabel for="specialization" value="Specialization" />
                                    <TextInput id="specialization" type="text" class="mt-1 block w-full" v-model="form.specialization" />
                                    <InputError class="mt-2" :message="form.errors.specialization" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Save Teacher
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
