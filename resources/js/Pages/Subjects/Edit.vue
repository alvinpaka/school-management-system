<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

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

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Edit Subject: {{ subject.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="bg-white border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <InputLabel for="name" value="Subject Name" />
                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="code" value="Subject Code" />
                                <TextInput id="code" type="text" class="mt-1 block w-full font-mono" v-model="form.code" required />
                                <InputError class="mt-2" :message="form.errors.code" />
                            </div>

                            <div>
                                <InputLabel for="type" value="Subject Type" />
                                <select id="type" v-model="form.type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-0 rounded-none">
                                    <option value="theory">Theory</option>
                                    <option value="practical">Practical</option>
                                    <option value="both">Both</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.type" />
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Update Subject
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
