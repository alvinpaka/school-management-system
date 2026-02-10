<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

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
    <Head title="Edit Exam" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Edit Exam: {{ exam.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="bg-white border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <InputLabel for="name" value="Exam Name" />
                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="subject" value="Subject" />
                                <select id="subject" v-model="form.subject_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-0 rounded-none">
                                    <option v-for="subject in subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.subject_id" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="date" value="Date" />
                                    <TextInput id="date" type="date" class="mt-1 block w-full" v-model="form.date" required />
                                    <InputError class="mt-2" :message="form.errors.date" />
                                </div>
                                <div>
                                    <InputLabel for="max_marks" value="Max Marks" />
                                    <TextInput id="max_marks" type="number" class="mt-1 block w-full" v-model="form.max_marks" required />
                                    <InputError class="mt-2" :message="form.errors.max_marks" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="start_time" value="Start Time" />
                                    <TextInput id="start_time" type="time" class="mt-1 block w-full" v-model="form.start_time" required />
                                    <InputError class="mt-2" :message="form.errors.start_time" />
                                </div>
                                <div>
                                    <InputLabel for="end_time" value="End Time" />
                                    <TextInput id="end_time" type="time" class="mt-1 block w-full" v-model="form.end_time" required />
                                    <InputError class="mt-2" :message="form.errors.end_time" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Update Exam
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
