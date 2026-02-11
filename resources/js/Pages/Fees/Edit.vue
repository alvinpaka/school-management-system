<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

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
                            <strong>Fee Details:</strong> {{ fee.fee_type }} - <span class="font-medium">UGX</span> {{ Number(fee.amount).toLocaleString() }}
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <Label for="status">Status</Label>
                                <select id="status" v-model="form.status" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    <option value="pending">Pending</option>
                                    <option value="partially_paid">Partially Paid</option>
                                    <option value="paid">Paid</option>
                                </select>
                                <p v-if="form.errors.status" class="text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.status }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="amount">Amount (UGX)</Label>
                                <Input id="amount" type="number" step="1" v-model="form.amount" required :class="{ 'border-red-500': form.errors.amount }" />
                                <p v-if="form.errors.amount" class="text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.amount }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="due_date">Due Date</Label>
                                <Input id="due_date" type="date" v-model="form.due_date" required :class="{ 'border-red-500': form.errors.due_date }" />
                                <p v-if="form.errors.due_date" class="text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.due_date }}
                                </p>
                            </div>

                            <div class="space-y-2" v-if="form.status === 'paid'">
                                <Label for="paid_date">Paid Date</Label>
                                <Input id="paid_date" type="date" v-model="form.paid_date" :class="{ 'border-red-500': form.errors.paid_date }" />
                                <p v-if="form.errors.paid_date" class="text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.paid_date }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="remarks">Remarks</Label>
                                <textarea id="remarks" v-model="form.remarks" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" :class="{ 'border-red-500': form.errors.remarks }"></textarea>
                                <p v-if="form.errors.remarks" class="text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.remarks }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <Link :href="route('fees.index')" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Cancel</Link>
                            <Button type="submit" :disabled="form.processing">
                                Update Status
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
