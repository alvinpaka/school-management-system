<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    classes: Array,
    sections: Array
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    academic_class_id: '',
    section_id: '',
    admission_number: '',
    date_of_birth: '',
});

const submit = () => {
    form.post(route('students.store'));
};
</script>

<template>
    <Head title="Add Student" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Add New Student
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

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="class" value="Class" />
                                    <select id="class" v-model="form.academic_class_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-0 rounded-none">
                                        <option value="" disabled>Select Class</option>
                                        <option v-for="cls in classes" :key="cls.id" :value="cls.id">{{ cls.name }}</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.academic_class_id" />
                                </div>

                                <div>
                                    <InputLabel for="section" value="Section" />
                                    <select id="section" v-model="form.section_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-0 rounded-none">
                                        <option value="" disabled>Select Section</option>
                                        <option v-for="sec in sections" :key="sec.id" :value="sec.id">{{ sec.name }}</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.section_id" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="admission_number" value="Admission Number" />
                                <TextInput id="admission_number" type="text" class="mt-1 block w-full font-mono" v-model="form.admission_number" required />
                                <InputError class="mt-2" :message="form.errors.admission_number" />
                            </div>

                            <div>
                                <InputLabel for="date_of_birth" value="Date of Birth" />
                                <TextInput id="date_of_birth" type="date" class="mt-1 block w-full" v-model="form.date_of_birth" />
                                <InputError class="mt-2" :message="form.errors.date_of_birth" />
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Save Student
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
