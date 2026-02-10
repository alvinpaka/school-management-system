<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    code: '',
    sections: ['A']
});

const addSection = () => {
    form.sections.push('');
};

const removeSection = (index) => {
    form.sections.splice(index, 1);
};

const submit = () => {
    form.post(route('classes.store'));
};
</script>

<template>
    <Head title="Add Class" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Add New Academic Class
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="bg-white border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <InputLabel for="name" value="Class Name (e.g. Grade 10)" />
                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="code" value="Class Code (e.g. G10)" />
                                <TextInput id="code" type="text" class="mt-1 block w-full font-mono" v-model="form.code" required />
                                <InputError class="mt-2" :message="form.errors.code" />
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <InputLabel value="Sections" />
                                    <button type="button" @click="addSection" class="text-xs font-bold text-blue-600 uppercase">+ Add Section</button>
                                </div>
                                <div v-for="(section, index) in form.sections" :key="index" class="flex items-center mb-2">
                                    <TextInput type="text" v-model="form.sections[index]" class="block w-full" placeholder="Section Name (e.g. A)" required />
                                    <button type="button" @click="removeSection(index)" class="ml-2 text-red-600" v-if="form.sections.length > 1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <InputError class="mt-2" v-for="(error, i) in form.errors" :key="i" :message="i.startsWith('sections.') ? error : ''" />
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Save Class
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
