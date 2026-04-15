<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import Pagination from '@/Components/ui/pagination.vue';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
    DialogFooter,
} from '@/Components/ui/dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { 
    Plus,
    Edit,
    Trash2,
    Search,
    Filter,
    Download,
    Eye,
    DollarSign,
    Calendar,
    CheckCircle,
    Clock,
    AlertCircle,
    User
} from 'lucide-vue-next';
import { ref, watch } from 'vue';

const { fees, filters, students } = defineProps({
    fees: Object,
    filters: Object,
    students: Array
});

const searchQuery = ref(filters?.search || '');
const isCreateDialogOpen = ref(false);

// Create fee form
const form = useForm({
    student_id: '',
    fee_type: '',
    amount: '',
    due_date: '',
    status: 'pending',
});

const submitForm = () => {
    form.post(route('fees.store'), {
        onSuccess: () => {
            isCreateDialogOpen.value = false;
            form.reset();
        },
    });
};

const deleteFee = (id) => {
    if (confirm('Are you sure you want to delete this fee record?')) {
        router.delete(route('fees.destroy', id));
    }
};

const getStatusClass = (status) => {
    switch (status) {
        case 'paid': return 'bg-forest/10 text-forest border-0';
        case 'pending': return 'bg-amber/10 text-amber border-0';
        case 'partially_paid': return 'bg-terracotta/10 text-terracotta border-0';
        default: return 'bg-warm-muted/10 text-warm-muted border-0';
    }
};

const handlePageChange = (page) => {
    const params = { page };
    if (searchQuery.value) {
        params.search = searchQuery.value;
    }
    router.get(route('fees.index'), params, { preserveState: true });
};

// Watch for search query changes
watch(searchQuery, (newValue) => {
    router.get(route('fees.index'), { 
        search: newValue, 
        page: 1 
    }, { preserveState: true });
}, { debounce: 300 });
</script>

<template>
    <Head title="Fees Management | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <DollarSign class="w-5 h-5 text-terracotta" />
                <span class="font-semibold text-warm-text text-dark-text">Fees</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 animate-fade-in-up">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter mb-2">Fees Management</h1>
                    <p class="text-warm-muted text-dark-muted font-medium">Manage student fees and payments</p>
                </div>
                <div class="flex items-center space-x-3">
                    <Button variant="outline" size="sm" class="border-terracotta/20 text-warm-muted text-dark-muted hover:text-terracotta">
                        <Download class="w-4 h-4 mr-2" />
                        Export Report
                    </Button>
                    <Dialog v-model:open="isCreateDialogOpen">
                        <DialogTrigger as-child>
                            <Button class="accent-terracotta text-white">
                                <Plus class="w-4 h-4 mr-2" />
                                Add Fee
                            </Button>
                        </DialogTrigger>
                        <DialogContent class="w-[95vw] max-w-4xl max-h-[90vh] overflow-y-auto card-warm">
                            <DialogHeader>
                                <DialogTitle class="text-xl font-bold text-warm-text text-dark-text">Add New Fee</DialogTitle>
                                <DialogDescription class="text-warm-muted text-dark-muted">
                                    Record a new fee payment for a student.
                                </DialogDescription>
                            </DialogHeader>
                            <form @submit.prevent="submitForm" class="space-y-4 py-4">
                                <div class="space-y-2">
                                    <Label for="student_id" class="text-warm-text text-dark-text">Student *</Label>
                                    <select
                                        id="student_id"
                                        v-model="form.student_id"
                                        class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-terracotta/30 text-warm-text text-dark-text"
                                        required
                                    >
                                        <option value="">Select a student</option>
                                        <option v-for="student in students" :key="student.id" :value="student.id">
                                            {{ student.user?.name }} ({{ student.admission_number }})
                                        </option>
                                    </select>
                                    <div v-if="form.errors.student_id" class="text-destructive text-sm">{{ form.errors.student_id }}</div>
                                </div>
                                <div class="space-y-2">
                                    <Label for="fee_type" class="text-warm-text text-dark-text">Fee Type *</Label>
                                    <Input
                                        id="fee_type"
                                        v-model="form.fee_type"
                                        type="text"
                                        placeholder="e.g., Tuition, Library, Sports"
                                        required
                                        class="border-terracotta/20 focus:ring-terracotta/30"
                                    />
                                    <div v-if="form.errors.fee_type" class="text-destructive text-sm">{{ form.errors.fee_type }}</div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <Label for="amount" class="text-warm-text text-dark-text">Amount (KES) *</Label>
                                        <Input
                                            id="amount"
                                            v-model="form.amount"
                                            type="number"
                                            placeholder="e.g., 50000"
                                            required
                                            class="border-terracotta/20 focus:ring-terracotta/30"
                                        />
                                        <div v-if="form.errors.amount" class="text-destructive text-sm">{{ form.errors.amount }}</div>
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="due_date" class="text-warm-text text-dark-text">Due Date *</Label>
                                        <Input
                                            id="due_date"
                                            v-model="form.due_date"
                                            type="date"
                                            required
                                            class="border-terracotta/20 focus:ring-terracotta/30"
                                        />
                                        <div v-if="form.errors.due_date" class="text-destructive text-sm">{{ form.errors.due_date }}</div>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <Label for="status" class="text-warm-text text-dark-text">Status</Label>
                                    <select
                                        id="status"
                                        v-model="form.status"
                                        class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-terracotta/30 text-warm-text text-dark-text"
                                    >
                                        <option value="pending">Pending</option>
                                        <option value="paid">Paid</option>
                                        <option value="partially_paid">Partially Paid</option>
                                    </select>
                                    <div v-if="form.errors.status" class="text-destructive text-sm">{{ form.errors.status }}</div>
                                </div>
                                <DialogFooter class="gap-3 pt-4 border-t border-terracotta/20">
                                    <Button type="button" variant="outline" @click="isCreateDialogOpen = false" class="border-terracotta/20">
                                        Cancel
                                    </Button>
                                    <Button type="submit" :disabled="form.processing" class="accent-terracotta text-white">
                                        <Plus class="w-4 h-4 mr-2" />
                                        {{ form.processing ? 'Creating...' : 'Create Fee' }}
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                </div>
            </div>

            <!-- Fees Card -->
            <div class="card-warm">
                <div class="p-6 border-b border-terracotta/20 bg-terracotta/5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-black text-warm-text text-dark-text">All Fees</h3>
                            <p class="text-sm text-warm-muted text-dark-muted">{{ fees.total }} fee records</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-warm-muted text-dark-muted" />
                                <input
                                    type="text"
                                    v-model="searchQuery"
                                    placeholder="Search fees..."
                                    class="pl-10 pr-4 py-2 border border-terracotta/20 rounded-lg focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta bg-white bg-dark-bg text-warm-text text-dark-text"
                                />
                            </div>
                            <Button variant="outline" size="sm" class="border-terracotta/20">
                                <Filter class="w-4 h-4" />
                            </Button>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-terracotta/5">
                            <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-warm-muted text-dark-muted border-b border-terracotta/20">
                                <th class="text-left py-4 px-6">Student</th>
                                <th class="text-left py-4 px-6">Fee Type</th>
                                <th class="text-left py-4 px-6">Amount</th>
                                <th class="text-left py-4 px-6">Due Date</th>
                                <th class="text-left py-4 px-6">Status</th>
                                <th class="text-right py-4 px-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="fee in fees.data" :key="fee.id" class="border-b border-terracotta/20 hover:bg-terracotta/5 transition-colors duration-300">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-terracotta/10 rounded-full flex items-center justify-center mr-3">
                                            <User class="w-5 h-5 text-terracotta" />
                                        </div>
                                        <div>
                                            <div class="font-medium text-warm-text text-dark-text">{{ fee.student?.user?.name }}</div>
                                            <div class="text-sm text-warm-muted text-dark-muted">{{ fee.student?.admission_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <Badge variant="secondary" class="bg-terracotta/10 text-terracotta border-0">
                                        {{ fee.fee_type || fee.type || 'Not specified' }}
                                    </Badge>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center text-sm text-warm-text text-dark-text">
                                        <span class="font-medium mr-1">KES</span>
                                        {{ Number(fee.amount).toLocaleString() }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center text-sm text-warm-text text-dark-text">
                                        <Calendar class="w-4 h-4 mr-1 text-terracotta" />
                                        {{ new Date(fee.due_date).toLocaleDateString() }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <Badge :class="getStatusClass(fee.status)">
                                        {{ fee.status }}
                                    </Badge>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-end">
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <Button variant="ghost" size="sm" class="hover:bg-terracotta/10">
                                                    <svg class="w-4 h-4 text-warm-muted text-dark-muted" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent align="end" class="card-warm rounded-2xl p-2 shadow-2xl">
                                                <DropdownMenuItem as-child>
                                                    <Link :href="route('fees.show', fee.id)" class="flex items-center p-2 rounded-xl hover:bg-terracotta/10">
                                                        <Eye class="w-4 h-4 mr-2" />
                                                        View Details
                                                    </Link>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem as-child>
                                                    <Link :href="route('fees.edit', fee.id)" class="flex items-center p-2 rounded-xl hover:bg-terracotta/10">
                                                        <Edit class="w-4 h-4 mr-2" />
                                                        Edit Fee
                                                    </Link>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem @click="deleteFee(fee.id)" class="flex items-center p-2 rounded-xl text-destructive hover:bg-destructive/10">
                                                    <Trash2 class="w-4 h-4 mr-2" />
                                                    Delete Fee
                                                </DropdownMenuItem>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="p-6 border-t border-terracotta/20 bg-terracotta/5">
                    <Pagination 
                        :data="fees" 
                        @page-change="handlePageChange"
                    />
                </div>
            </div>
        </div>
    </Sidebar>
</template>