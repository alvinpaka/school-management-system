<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    fee: Object
});

const form = useForm({
    amount: props.fee.amount,
    due_date: props.fee.due_date,
    status: props.fee.status,
    paid_date: props.fee.paid_date || '',
    remarks: props.fee.remarks || ''
});

const submit = () => {
    form.put(route('fees.update', props.fee.id));
};
</script>

<template>
    <Head title="Update Fee Status" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Update Fee Record: {{ fee.student.user.name }} ({{ fee.fee_type }})
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <form @submit.prevent="submit" class="max-w-xl">
                        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                            <strong>Fee Details:</strong> {{ fee.fee_type }} - ${{ fee.amount }}
                        </div>

                        <div class="mt-4">
                            <InputLabel for="status" value="Status" />
                            <select id="status" v-model="form.status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-0 rounded-none">
                                <option value="pending">Pending</option>
                                <option value="partially_paid">Partially Paid</option>
                                <option value="paid">Paid</option>
                            </select>
                            <InputError :message="form.errors.status" class="mt-2" />
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

                        <div class="mt-4" v-if="form.status === 'paid'">
                            <InputLabel for="paid_date" value="Paid Date" />
                            <TextInput id="paid_date" type="date" class="mt-1 block w-full" v-model="form.paid_date" />
                            <InputError :message="form.errors.paid_date" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="remarks" value="Remarks" />
                            <textarea id="remarks" v-model="form.remarks" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-0 rounded-none"></textarea>
                            <InputError :message="form.errors.remarks" class="mt-2" />
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <Link :href="route('fees.index')" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Cancel</Link>
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Update Status
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
