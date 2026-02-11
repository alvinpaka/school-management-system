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
    DollarSign,
    Calendar,
    FileText,
    User
} from 'lucide-vue-next';

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

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Button variant="ghost" size="sm" :href="route('fees.index')">
                    <ArrowLeft class="w-4 h-4 mr-2" />
                    Back to Fees
                </Button>
                <span class="text-gray-400">|</span>
                <span>Record New Fee</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Form Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Fee Information</CardTitle>
                    <CardDescription>Enter details for the new fee record</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Basic Information</h3>
                                
                                <div>
                                    <Label for="student_id">Student</Label>
                                    <select
                                        id="student_id"
                                        v-model="form.student_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-lg px-3 py-2"
                                        required
                                    >
                                        <option value="">Select a student</option>
                                        <option v-for="student in props.students" :key="student.id" :value="student.id">
                                            {{ student.name }} ({{ student.admission_number }})
                                        </option>
                                    </select>
                                    <div v-if="form.errors.student_id" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.student_id }}
                                    </div>
                                </div>

                                <div>
                                    <Label for="fee_type">Fee Type</Label>
                                    <select
                                        id="fee_type"
                                        v-model="form.fee_type"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-lg px-3 py-2"
                                        required
                                    >
                                        <option value="">Select fee type</option>
                                        <option value="tuition">Tuition Fee</option>
                                        <option value="exam">Exam Fee</option>
                                        <option value="library">Library Fee</option>
                                        <option value="lab">Lab Fee</option>
                                        <option value="sports">Sports Fee</option>
                                        <option value="transport">Transport Fee</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <div v-if="form.errors.fee_type" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.fee_type }}
                                    </div>
                                </div>

                                <div>
                                    <Label for="amount">Amount</Label>
                                    <Input
                                        id="amount"
                                        v-model="form.amount"
                                        type="number"
                                        class="mt-1 block w-full"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01"
                                        required
                                    />
                                    <div v-if="form.errors.amount" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.amount }}
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Additional Information</h3>
                                
                                <div>
                                    <Label for="due_date">Due Date</Label>
                                    <Input
                                        id="due_date"
                                        v-model="form.due_date"
                                        type="date"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                    <div v-if="form.errors.due_date" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.due_date }}
                                    </div>
                                </div>

                                <div>
                                    <Label for="remarks">Remarks</Label>
                                    <textarea
                                        id="remarks"
                                        v-model="form.remarks"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-lg px-3 py-2"
                                        rows="4"
                                        placeholder="Additional notes or comments"
                                    ></textarea>
                                    <div v-if="form.errors.remarks" class="text-red-600 text-sm mt-1">
                                        {{ form.errors.remarks }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <Button variant="outline" type="button" :href="route('fees.index')">
                                Cancel
                            </Button>
                            <Button type="submit" :disabled="form.processing">
                                <Save class="w-4 h-4 mr-2" />
                                {{ form.processing ? 'Recording...' : 'Record Fee' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
