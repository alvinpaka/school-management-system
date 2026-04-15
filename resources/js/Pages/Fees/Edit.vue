<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';

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
    <Head title="Update Fee Status | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <span class="font-semibold text-warm-text text-dark-text">Update Fee Record</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 animate-fade-in-up">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter mb-2">Update Fee Record</h1>
                    <p class="text-warm-muted text-dark-muted font-medium">
                        {{ fee.student?.user?.name }} <span class="mx-2">•</span> {{ fee.fee_type }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <Link :href="route('fees.index')">
                        <Button variant="outline" class="border-terracotta/20 text-warm-muted text-dark-muted hover:text-terracotta">
                            Back to Fees
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Edit Form Card -->
            <div class="card-warm">
                <div class="p-6 border-b border-terracotta/20">
                    <h3 class="text-lg font-black text-warm-text text-dark-text">Fee Details</h3>
                    <p class="text-sm text-warm-muted text-dark-muted mt-1">
                        {{ fee.fee_type }} - <span class="font-medium">KES</span> {{ Number(fee.amount).toLocaleString() }}
                    </p>
                </div>
                <div class="p-6">
                    <form @submit.prevent="submit" class="max-w-xl">
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <Label for="status" class="text-warm-text text-dark-text">Status</Label>
                                <select 
                                    id="status" 
                                    v-model="form.status" 
                                    class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-terracotta/30 text-warm-text text-dark-text"
                                >
                                    <option value="pending">Pending</option>
                                    <option value="partially_paid">Partially Paid</option>
                                    <option value="paid">Paid</option>
                                </select>
                                <p v-if="form.errors.status" class="text-sm text-destructive">
                                    {{ form.errors.status }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="amount" class="text-warm-text text-dark-text">Amount (KES)</Label>
                                <Input 
                                    id="amount" 
                                    type="number" 
                                    step="1" 
                                    v-model="form.amount" 
                                    required 
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                    :class="{ 'border-destructive focus:ring-destructive/30': form.errors.amount }" 
                                />
                                <p v-if="form.errors.amount" class="text-sm text-destructive">
                                    {{ form.errors.amount }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="due_date" class="text-warm-text text-dark-text">Due Date</Label>
                                <Input 
                                    id="due_date" 
                                    type="date" 
                                    v-model="form.due_date" 
                                    required 
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                    :class="{ 'border-destructive focus:ring-destructive/30': form.errors.due_date }" 
                                />
                                <p v-if="form.errors.due_date" class="text-sm text-destructive">
                                    {{ form.errors.due_date }}
                                </p>
                            </div>

                            <div class="space-y-2" v-if="form.status === 'paid'">
                                <Label for="paid_date" class="text-warm-text text-dark-text">Paid Date</Label>
                                <Input 
                                    id="paid_date" 
                                    type="date" 
                                    v-model="form.paid_date" 
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                    :class="{ 'border-destructive focus:ring-destructive/30': form.errors.paid_date }" 
                                />
                                <p v-if="form.errors.paid_date" class="text-sm text-destructive">
                                    {{ form.errors.paid_date }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="remarks" class="text-warm-text text-dark-text">Remarks</Label>
                                <textarea 
                                    id="remarks" 
                                    v-model="form.remarks" 
                                    class="flex min-h-[80px] w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-terracotta/30 placeholder:text-warm-muted text-warm-text text-dark-text"
                                    :class="{ 'border-destructive focus:ring-destructive/30': form.errors.remarks }"
                                ></textarea>
                                <p v-if="form.errors.remarks" class="text-sm text-destructive">
                                    {{ form.errors.remarks }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-3 pt-4 border-t border-terracotta/20">
                            <Link :href="route('fees.index')" class="text-sm text-warm-muted text-dark-muted hover:text-terracotta transition-colors">
                                Cancel
                            </Link>
                            <Button type="submit" :disabled="form.processing" class="accent-terracotta text-white">
                                Update Status
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Sidebar>
</template>