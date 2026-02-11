<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
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

const props = defineProps({
    fees: Object
});

const deleteFee = (id) => {
    if (confirm('Are you sure you want to delete this fee record?')) {
        router.delete(route('fees.destroy', id));
    }
};

const getStatusClass = (status) => {
    switch (status) {
        case 'paid': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'pending': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
        case 'partially_paid': return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
    }
};
</script>

<template>
    <Head title="Fees Management" />

    <Sidebar>
        <template #header-title>
            Fees Management
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Fees Management</h2>
                        <p class="text-gray-600 dark:text-gray-400">Manage student fees and payments</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <Button variant="outline" size="sm">
                            <Download class="w-4 h-4 mr-2" />
                            Export Report
                        </Button>
                        <Link :href="route('fees.create')">
                            <Button>
                                <Plus class="w-4 h-4 mr-2" />
                                Add Fee
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Fees Card -->
            <Card>
                <CardHeader>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <CardTitle>All Fees</CardTitle>
                            <CardDescription>{{ fees.data?.length || 0 }} fee records</CardDescription>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                                <input
                                    type="text"
                                    placeholder="Search fees..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                />
                            </div>
                            <Button variant="outline" size="sm">
                                <Filter class="w-4 h-4" />
                            </Button>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Student</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Fee Type</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Amount</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Due Date</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Status</th>
                                    <th class="text-right py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="fee in fees.data" :key="fee.id" class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                                <User class="w-5 h-5 text-white" />
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ fee.student?.user?.name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ fee.student?.admission_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <Badge variant="secondary">
                                            {{ fee.type }}
                                        </Badge>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium mr-1">UGX</span>
                                            {{ Number(fee.amount).toLocaleString() }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <Calendar class="w-4 h-4 mr-1" />
                                            {{ new Date(fee.due_date).toLocaleDateString() }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <Badge :class="getStatusClass(fee.status)">
                                            {{ fee.status }}
                                        </Badge>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-end">
                                            <DropdownMenu>
                                                <DropdownMenuTrigger as-child>
                                                    <Button variant="ghost" size="sm">
                                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                        </svg>
                                                    </Button>
                                                </DropdownMenuTrigger>
                                                <DropdownMenuContent align="end">
                                                    <DropdownMenuItem as-child>
                                                        <Link :href="route('fees.show', fee.id)" class="flex items-center">
                                                            <Eye class="w-4 h-4 mr-2" />
                                                            View Details
                                                        </Link>
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem as-child>
                                                        <Link :href="route('fees.edit', fee.id)" class="flex items-center">
                                                            <Edit class="w-4 h-4 mr-2" />
                                                            Edit Fee
                                                        </Link>
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem @click="deleteFee(fee.id)" class="flex items-center text-red-600">
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
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
                                        
