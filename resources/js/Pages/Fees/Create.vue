<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    students: Array
});

const form = useForm({
    student_id: '',
    amount: '',
    fee_type: '',
    due_date: '',
    remarks: ''
});

const submit = () => {
    form.post(route('fees.store'));
};
</script>

<template>
    <Head title="Record Fee" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Record New Fee
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <form @submit.prevent="submit" class="max-w-xl">
                        <div>
                            <InputLabel for="student" value="Student" />
                            <select id="student" v-model="form.student_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-0 rounded-none">
                                <option value="">Select Student</option>
                                <option v-for="student in students" :key="student.id" :value="student.id">
                                    {{ student.user.name }} ({{ student.admission_number }})
                                </option>
                            </select>
                            <InputError :message="form.errors.student_id" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="fee_type" value="Fee Type" />
                            <TextInput id="fee_type" type="text" class="mt-1 block w-full" v-model="form.fee_type" placeholder="e.g. Tuition Fee Q1" required />
                            <InputError :message="form.errors.fee_type" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="amount" value="Amount ($)" />
                            <TextInput id="amount" type="number" step="0.01" class="mt-1 block w-full" v-model="form.amount" required />
                            <InputError :message="form.errors.amount" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="due_date" value="Due Date" />
                            <TextInput id="due_date" type="date" class="mt-1 block w-full" v-model="form.due_date" required />
                            <InputError :message="form.errors.due_date" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="remarks" value="Remarks" />
                            <textarea id="remarks" v-model="form.remarks" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-0 rounded-none"></textarea>
                            <InputError :message="form.errors.remarks" class="mt-2" />
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <Link :href="route('fees.index')" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Cancel</Link>
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Save Fee Record
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
