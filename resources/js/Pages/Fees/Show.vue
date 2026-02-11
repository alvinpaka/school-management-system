<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { 
    ArrowLeft,
    User,
    DollarSign,
    Calendar,
    CheckCircle,
    Clock,
    AlertCircle,
    Edit,
    Trash2
} from 'lucide-vue-next';

const props = defineProps({
    fee: {
        type: Object,
        required: true
    }
});

const deleteFee = () => {
    if (confirm('Are you sure you want to delete this fee?')) {
        router.delete(route('fees.destroy', props.fee.id));
    }
};
</script>

<template>
    <Head title="Fee Details" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('fees.index')">
                    <Button variant="ghost" size="sm">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Back to Fees
                    </Button>
                </Link>
                <span class="text-gray-400">|</span>
                <span>Fee Details</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Fee Information Card -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <DollarSign class="w-5 h-5" />
                        <span>{{ fee.title }}</span>
                    </CardTitle>
                    <CardDescription>
                        Fee information and payment details
                    </CardDescription>
                </CardHeader>
                <CardContent class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student Information -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4 flex items-center space-x-2">
                                <User class="w-5 h-5" />
                                <span>Student Information</span>
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <User class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Student:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    {{ fee.student?.user?.name || 'Not specified' }}
                                </p>
                            </div>
                        </div>

                        <!-- Fee Details -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4 flex items-center space-x-2">
                                <DollarSign class="w-5 h-5" />
                                <span>Fee Details</span>
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <DollarSign class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Amount:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium mr-1">UGX</span>
                                    {{ Number(fee.amount).toLocaleString() }}
                                </p>
                                
                                <div class="flex items-center space-x-2">
                                    <Calendar class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Due Date:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    {{ fee.due_date || 'Not specified' }}
                                </p>
                                
                                <div class="flex items-center space-x-2">
                                    <CheckCircle class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Status:</span>
                                </div>
                                <p class="ml-6">
                                    <Badge :variant="fee.status === 'paid' ? 'default' : 'secondary'">
                                        {{ fee.status || 'Pending' }}
                                    </Badge>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold mb-4">Additional Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <Calendar class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Created:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    {{ new Date(fee.created_at).toLocaleDateString() }}
                                </p>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <Clock class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm font-medium">Last Updated:</span>
                                </div>
                                <p class="ml-6 text-sm text-gray-600 dark:text-gray-400">
                                    {{ new Date(fee.updated_at).toLocaleDateString() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                        <Link :href="route('fees.index')">
                            <Button variant="outline">
                                <ArrowLeft class="w-4 h-4 mr-2" />
                                Back to List
                            </Button>
                        </Link>
                        
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button>
                                    Actions
                                    <svg class="w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end">
                                <DropdownMenuItem as-child>
                                    <Link :href="route('fees.edit', fee.id)" class="flex items-center">
                                        <Edit class="w-4 h-4 mr-2" />
                                        Edit Fee
                                    </Link>
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="deleteFee" class="flex items-center text-red-600">
                                    <Trash2 class="w-4 h-4 mr-2" />
                                    Delete Fee
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
