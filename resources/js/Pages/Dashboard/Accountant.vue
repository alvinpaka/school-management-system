<script setup>
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Separator } from '@/components/ui/separator';
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { 
    Users,
    GraduationCap,
    BookOpen,
    Calendar,
    DollarSign,
    TrendingUp,
    TrendingDown,
    UserCheck,
    FileText,
    Bell,
    Clock,
    CheckCircle,
    AlertCircle,
    Home,
    Award,
    BarChart3,
    Receipt,
    Calculator,
    Download,
    PieChart,
    Wallet,
    CreditCard,
    ArrowUpRight,
    ArrowDownRight,
    Sparkles,
    ChevronRight,
    Plus,
    Filter,
    Search,
    Zap,
    TrendingUpIcon,
    BadgeDollarSign,
    Banknote,
    CircleDollarSign
} from 'lucide-vue-next';

defineProps({
    accountant: Object,
    stats: Object
});

const quickActions = [
    {
        title: 'Collect Fees',
        description: 'Record payment',
        icon: DollarSign,
        color: 'from-emerald-500 to-emerald-600',
        href: route('fees.index')
    },
    {
        title: 'Create Invoice',
        description: 'Generate invoice',
        icon: Receipt,
        color: 'from-blue-500 to-blue-600',
        href: '#'
    },
    {
        title: 'View Reports',
        description: 'Financial reports',
        icon: BarChart3,
        color: 'from-purple-500 to-purple-600',
        href: '#'
    },
    {
        title: 'Expenses',
        description: 'Track expenses',
        icon: Calculator,
        color: 'from-red-500 to-red-600',
        href: '#'
    },
    {
        title: 'Statements',
        description: 'Account statements',
        icon: FileText,
        color: 'from-indigo-500 to-indigo-600',
        href: '#'
    },
    {
        title: 'Export Data',
        description: 'Download reports',
        icon: Download,
        color: 'from-orange-500 to-orange-600',
        href: '#'
    }
];

const recentTransactions = [
    {
        title: 'Fee Payment Received',
        description: 'John Doe - Class 10A',
        amount: 400000,
        type: 'income',
        time: '2 hours ago',
        status: 'completed'
    },
    {
        title: 'Salary Payment',
        description: 'Teacher salaries for February',
        amount: 2500000,
        type: 'expense',
        time: '5 hours ago',
        status: 'completed'
    },
    {
        title: 'Invoice Generated',
        description: 'Tuition fees - Class 10A',
        amount: 150000,
        type: 'invoice',
        time: '1 day ago',
        status: 'pending'
    },
    {
        title: 'Utility Bill Payment',
        description: 'Electricity & Water',
        amount: 350000,
        type: 'expense',
        time: '1 day ago',
        status: 'completed'
    },
    {
        title: 'Exam Fee Collection',
        description: 'Mid-term examination fees',
        amount: 800000,
        type: 'income',
        time: '2 days ago',
        status: 'completed'
    }
];

const financialSummary = {
    totalRevenue: 12500000,
    totalExpenses: 8500000,
    pendingFees: 2000000,
    collectedFees: 10500000,
    monthlyGrowth: '+12.5%',
    profitMargin: '32%'
};

const pendingPayments = [
    { name: 'Alice Johnson', class: 'Class 9B', amount: 250000, dueDate: 'Feb 20, 2026' },
    { name: 'Michael Brown', class: 'Class 10A', amount: 300000, dueDate: 'Feb 22, 2026' },
    { name: 'Sarah Williams', class: 'Class 8C', amount: 200000, dueDate: 'Feb 25, 2026' }
];

const formatCurrency = (amount) => {
    return `UGX ${(amount / 1000000).toFixed(1)}M`;
};

const getInitials = (name) => {
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
};
</script>

<template>
    <Head title="Accountant Dashboard" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Wallet class="w-5 h-5" />
                <span class="font-semibold">Financial Dashboard</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Welcome Header -->
            <Card class="overflow-hidden border-0">
                <div class="h-24"></div>
                <CardContent class="relative pt-0 pb-6 -mt-12">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between">
                        <div class="flex items-center space-x-4">
                            <Avatar class="w-20 h-20 border-2 border-gray-200 dark:border-gray-700">
                                <AvatarImage 
                                    v-if="$page.props.auth.user.photo"
                                    :src="`/storage/${$page.props.auth.user.photo}`" 
                                    :alt="$page.props.auth.user.name"
                                />
                                <AvatarFallback class="text-2xl font-bold bg-emerald-500 text-white">
                                    {{ getInitials($page.props.auth.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <div class="mt-4">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    Welcome back, {{ $page.props.auth.user.name }}
                                    <BadgeDollarSign class="w-6 h-6 text-emerald-500" />
                                </h1>
                                <p class="text-base text-gray-600 dark:text-gray-400">
                                    Here's your financial overview today
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 mb-2">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Financial Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Revenue -->
                <Card class="hover:shadow-lg transition-all duration-200 border-emerald-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                                <TrendingUp class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <Badge variant="secondary" class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                <ArrowUpRight class="w-3 h-3 mr-1" />
                                {{ financialSummary.monthlyGrowth }}
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Revenue</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(financialSummary.totalRevenue) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">+1.2M</span> this month
                        </p>
                    </CardContent>
                </Card>

                <!-- Collected Fees -->
                <Card class="hover:shadow-lg transition-all duration-200 border-blue-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                                <CheckCircle class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                            </div>
                            <Badge variant="secondary" class="bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                Collected
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Collected Fees</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(financialSummary.collectedFees) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-blue-600 dark:text-blue-400 font-medium">84%</span> collection rate
                        </p>
                    </CardContent>
                </Card>

                <!-- Pending Fees -->
                <Card class="hover:shadow-lg transition-all duration-200 border-orange-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                                <AlertCircle class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                            </div>
                            <Badge variant="secondary" class="bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                Pending
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Pending Fees</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(financialSummary.pendingFees) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-orange-600 dark:text-orange-400 font-medium">15</span> students pending
                        </p>
                    </CardContent>
                </Card>

                <!-- Total Expenses -->
                <Card class="hover:shadow-lg transition-all duration-200 border-red-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-xl">
                                <ArrowDownRight class="w-6 h-6 text-red-600 dark:text-red-400" />
                            </div>
                            <Badge variant="secondary" class="bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                -5.2%
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Expenses</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(financialSummary.totalExpenses) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">-450K</span> vs last month
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Profit Overview Card -->
            <Card class="bg-emerald-500 border-0 text-white">
                <CardContent class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-emerald-100 mb-2 flex items-center gap-2">
                                <Sparkles class="w-4 h-4" />
                                Net Profit (This Month)
                            </p>
                            <p class="text-4xl font-bold mb-2">
                                {{ formatCurrency(financialSummary.totalRevenue - financialSummary.totalExpenses) }}
                            </p>
                            <div class="flex items-center gap-4 text-sm">
                                <Badge variant="secondary" class="bg-white/20 text-white border-0">
                                    Profit Margin: {{ financialSummary.profitMargin }}
                                </Badge>
                                <span class="flex items-center gap-1">
                                    <TrendingUp class="w-4 h-4" />
                                    {{ financialSummary.monthlyGrowth }} growth
                                </span>
                            </div>
                        </div>
                        <div class="p-4 bg-white/10 rounded-2xl backdrop-blur-sm">
                            <CircleDollarSign class="w-12 h-12" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Quick Actions -->
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle class="flex items-center gap-2">
                                <Zap class="w-5 h-5 text-emerald-500" />
                                Quick Actions
                            </CardTitle>
                            <CardDescription>
                                Financial management tools for quick access
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <Link
                            v-for="action in quickActions"
                            :key="action.title"
                            :href="action.href"
                            class="group relative overflow-hidden rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-transparent transition-all duration-300"
                        >
                            <div class="absolute inset-0 bg-gradient-to-br opacity-0 group-hover:opacity-100 transition-opacity duration-300" :class="action.color"></div>
                            <div class="relative p-4 flex flex-col items-center text-center space-y-3">
                                <div class="p-3 rounded-xl bg-gray-100 dark:bg-gray-800 group-hover:bg-white/20 transition-colors duration-300">
                                    <component :is="action.icon" class="w-6 h-6 text-gray-700 dark:text-gray-300 group-hover:text-white transition-colors duration-300" />
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors duration-300">
                                        {{ action.title }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-white/80 transition-colors duration-300 mt-1">
                                        {{ action.description }}
                                    </p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </CardContent>
            </Card>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Transactions -->
                <div class="lg:col-span-2 space-y-6">
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <div>
                                    <CardTitle class="flex items-center gap-2">
                                        <Receipt class="w-5 h-5" />
                                        Recent Transactions
                                    </CardTitle>
                                    <CardDescription>
                                        Latest financial activities
                                    </CardDescription>
                                </div>
                                <div class="flex items-center gap-2">
                                    <Button variant="ghost" size="sm">
                                        <Filter class="w-4 h-4" />
                                    </Button>
                                    <Button variant="ghost" size="sm">
                                        View All
                                        <ChevronRight class="w-4 h-4 ml-1" />
                                    </Button>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div
                                    v-for="(transaction, index) in recentTransactions"
                                    :key="index"
                                    class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200"
                                >
                                    <div 
                                        :class="[
                                            'p-2.5 rounded-lg flex-shrink-0',
                                            transaction.type === 'income' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 
                                            transaction.type === 'expense' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : 
                                            'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'
                                        ]"
                                    >
                                        <component 
                                            :is="transaction.type === 'income' ? TrendingUp : transaction.type === 'expense' ? TrendingDown : Receipt" 
                                            class="w-5 h-5" 
                                        />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ transaction.title }}</h4>
                                            <Badge 
                                                :class="[
                                                    transaction.type === 'income' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 
                                                    transaction.type === 'expense' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 
                                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                                ]"
                                            >
                                                {{ transaction.type === 'income' ? '+' : transaction.type === 'expense' ? '-' : '' }}UGX {{ (transaction.amount / 1000).toFixed(0) }}K
                                            </Badge>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ transaction.description }}</p>
                                        <div class="flex items-center justify-between">
                                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                                <Clock class="w-3 h-3" />
                                                {{ transaction.time }}
                                            </p>
                                            <Badge 
                                                variant="outline" 
                                                :class="transaction.status === 'completed' ? 'border-emerald-300 text-emerald-700 dark:border-emerald-700 dark:text-emerald-400' : 'border-orange-300 text-orange-700 dark:border-orange-700 dark:text-orange-400'"
                                            >
                                                {{ transaction.status }}
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Revenue Chart Placeholder -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <PieChart class="w-5 h-5" />
                                Revenue Overview
                            </CardTitle>
                            <CardDescription>
                                Monthly revenue and expense breakdown
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="h-64 flex items-center justify-center bg-emerald-50 dark:from-emerald-900/10 dark:to-teal-900/10 rounded-xl">
                                <div class="text-center">
                                    <BarChart3 class="w-16 h-16 text-emerald-400 mx-auto mb-4" />
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Financial Performance</p>
                                    <div class="flex items-center justify-center gap-4 text-xs">
                                        <div class="flex items-center gap-2">
                                            <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                                            <span class="text-gray-600 dark:text-gray-400">Revenue: {{ formatCurrency(financialSummary.totalRevenue) }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                            <span class="text-gray-600 dark:text-gray-400">Expenses: {{ formatCurrency(financialSummary.totalExpenses) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Pending Payments -->
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <CardTitle class="flex items-center gap-2">
                                    <CreditCard class="w-5 h-5" />
                                    Pending Payments
                                    <Badge variant="destructive" class="ml-1">{{ pendingPayments.length }}</Badge>
                                </CardTitle>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div
                                    v-for="payment in pendingPayments"
                                    :key="payment.name"
                                    class="p-3 rounded-lg border border-orange-200 dark:border-orange-800 bg-orange-50 dark:bg-orange-900/10"
                                >
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ payment.name }}</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ payment.class }}</p>
                                        </div>
                                        <Badge variant="outline" class="border-orange-300 text-orange-700 dark:border-orange-700 dark:text-orange-400">
                                            UGX {{ (payment.amount / 1000).toFixed(0) }}K
                                        </Badge>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                        <Calendar class="w-3 h-3" />
                                        Due: {{ payment.dueDate }}
                                    </p>
                                </div>
                            </div>
                            <Button variant="outline" class="w-full mt-3" size="sm">
                                <Plus class="w-4 h-4 mr-2" />
                                Send Reminders
                            </Button>
                        </CardContent>
                    </Card>

                    <!-- Quick Summary -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <BarChart3 class="w-5 h-5" />
                                Financial Summary
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Students</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ stats?.students_count || 0 }}</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Fee Collected</span>
                                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">84%</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Profit Margin</span>
                                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">{{ financialSummary.profitMargin }}</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Monthly Growth</span>
                                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">{{ financialSummary.monthlyGrowth }}</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Quick Actions Button -->
                    <Card class="bg-blue-500 border-0 text-white">
                        <CardContent class="p-6 text-center">
                            <Download class="w-12 h-12 mx-auto mb-3 opacity-90" />
                            <h3 class="font-semibold mb-2">Monthly Report</h3>
                            <p class="text-sm text-blue-100 mb-4">Download comprehensive financial report</p>
                            <Button variant="secondary" size="sm" class="w-full">
                                <Download class="w-4 h-4 mr-2" />
                                Download Report
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </Sidebar>
</template>