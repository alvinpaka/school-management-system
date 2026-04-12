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

        <div class="mx-auto max-w-7xl space-y-8 animate-fade-in-up">
            <!-- Professional Header Section -->
            <div class="relative overflow-hidden bg-card shadow-sm rounded-3xl lg:rounded-[3rem] border border-border p-6 lg:p-12">
                <div class="flex flex-col md:flex-row items-center md:items-end gap-6 lg:gap-8">
                    <div class="w-20 h-20 lg:w-24 lg:h-24 bg-muted rounded-2xl lg:rounded-[2.5rem] flex items-center justify-center border border-border shadow-inner overflow-hidden">
                        <Avatar class="w-full h-full rounded-none">
                            <AvatarImage 
                                v-if="$page.props.auth.user.photo"
                                :src="`/storage/${$page.props.auth.user.photo}`" 
                                :alt="$page.props.auth.user.name"
                                class="object-cover"
                            />
                            <AvatarFallback class="text-xl lg:text-2xl font-black bg-primary text-primary-foreground">
                                {{ getInitials($page.props.auth.user.name) }}
                            </AvatarFallback>
                        </Avatar>
                    </div>
                    
                    <div class="text-center md:text-left space-y-2 lg:space-y-4">
                        <div class="flex items-center justify-center md:justify-start gap-2">
                            <Badge class="bg-primary/10 text-primary border-0 font-black uppercase text-[9px] tracking-widest px-3 lg:px-4 py-1 h-7">
                                Financial Command
                            </Badge>
                            <span class="text-[10px] font-black text-muted-foreground uppercase tracking-widest">Active Session</span>
                        </div>
                        <h1 class="text-3xl lg:text-5xl font-black text-foreground tracking-tighter leading-tight">
                            Welcome, {{ accountant?.name || $page.props.auth.user.name }}
                        </h1>
                        <p class="text-muted-foreground font-bold text-xs lg:text-sm uppercase tracking-wide">
                            {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                        </p>
                    </div>
                </div>
            </div>
            <!-- Financial Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                <!-- Revenue Total -->
                <div class="bg-card p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] border border-border shadow-sm group hover:border-emerald-500/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/10 flex items-center justify-center text-emerald-600 font-bold shadow-inner transition-transform group-hover:scale-110">
                            <TrendingUp class="w-6 h-6" />
                        </div>
                        <Badge class="bg-emerald-500 text-white border-0 font-black text-[9px] uppercase tracking-widest">{{ financialSummary.monthlyGrowth }}</Badge>
                    </div>
                    <p class="text-xs font-black text-muted-foreground/80 uppercase tracking-widest mb-1">Total Revenue</p>
                    <h3 class="text-3xl font-black text-foreground tracking-tighter leading-none">{{ formatCurrency(financialSummary.totalRevenue) }}</h3>
                </div>

                <!-- Collected Statistics -->
                <div class="bg-card p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] border border-border shadow-sm group hover:border-blue-500/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/10 flex items-center justify-center text-blue-600 font-bold shadow-inner transition-transform group-hover:scale-110">
                            <CheckCircle class="w-6 h-6" />
                        </div>
                        <Badge class="bg-muted border-0 text-muted-foreground font-black text-[9px] uppercase tracking-widest">84% Rate</Badge>
                    </div>
                    <p class="text-xs font-black text-muted-foreground/80 uppercase tracking-widest mb-1">Collected Fees</p>
                    <h3 class="text-3xl font-black text-foreground tracking-tighter leading-none">{{ formatCurrency(financialSummary.collectedFees) }}</h3>
                </div>

                <!-- Pending Assets -->
                <div class="bg-card p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] border border-border shadow-sm group hover:border-orange-500/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 dark:bg-orange-900/10 flex items-center justify-center text-orange-600 font-bold shadow-inner transition-transform group-hover:scale-110">
                            <AlertCircle class="w-6 h-6" />
                        </div>
                        <Badge class="bg-orange-500 text-white border-0 font-black text-[9px] uppercase tracking-widest">Req.</Badge>
                    </div>
                    <p class="text-xs font-black text-muted-foreground/80 uppercase tracking-widest mb-1">Pending Fees</p>
                    <h3 class="text-3xl font-black text-foreground tracking-tighter leading-none">{{ formatCurrency(financialSummary.pendingFees) }}</h3>
                </div>

                <!-- Expense Control -->
                <div class="bg-card p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] border border-border shadow-sm group hover:border-rose-500/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-900/10 flex items-center justify-center text-rose-600 font-bold shadow-inner transition-transform group-hover:scale-110">
                            <ArrowDownRight class="w-6 h-6" />
                        </div>
                        <Badge class="bg-muted border-0 text-muted-foreground font-black text-[9px] uppercase tracking-widest">-5.2%</Badge>
                    </div>
                    <p class="text-xs font-black text-muted-foreground/80 uppercase tracking-widest mb-1">Total Expenses</p>
                    <h3 class="text-3xl font-black text-foreground tracking-tighter leading-none">{{ formatCurrency(financialSummary.totalExpenses) }}</h3>
                </div>
            </div>

            <!-- Profit Intelligence Card -->
            <div class="bg-primary rounded-3xl lg:rounded-[3rem] p-6 lg:p-10 text-primary-foreground shadow-xl shadow-primary/10 relative overflow-hidden group">
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 lg:gap-8">
                    <div class="text-center md:text-left space-y-4">
                        <div class="flex items-center justify-center md:justify-start gap-4">
                            <div class="bg-white/10 rounded-xl px-4 py-1 border border-white/20">
                                <span class="text-[10px] font-black uppercase tracking-widest text-primary-foreground/90">Net Profit (Monthly)</span>
                            </div>
                            <Badge class="bg-background text-primary border-white/20 font-black text-[9px] uppercase leading-none">Healthy Margin</Badge>
                        </div>
                        <h2 class="text-4xl lg:text-6xl font-black tracking-tighter leading-tight">
                            {{ formatCurrency(financialSummary.totalRevenue - financialSummary.totalExpenses) }}
                        </h2>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 lg:gap-6 text-sm font-bold text-primary-foreground/80">
                            <span class="flex items-center gap-2">
                                <TrendingUp class="w-5 h-5" />
                                {{ financialSummary.monthlyGrowth }} Operational Growth
                            </span>
                            <span class="flex items-center gap-2">
                                <BarChart3 class="w-5 h-5" />
                                Profit Margin: {{ financialSummary.profitMargin }}
                            </span>
                        </div>
                    </div>
                    <div class="w-20 h-20 lg:w-24 lg:h-24 bg-white/10 rounded-[2rem] lg:rounded-[2.5rem] flex items-center justify-center border border-white/20 shadow-2xl relative lg:group-hover:scale-110 transition-transform duration-500">
                        <CircleDollarSign class="w-10 h-10 lg:w-12 lg:h-12 text-white" />
                    </div>
                </div>
            </div>

            <!-- Quick Access Intelligence -->
            <Card class="bg-card shadow-sm border-border rounded-3xl lg:rounded-[3rem] overflow-hidden">
                <CardHeader class="p-6 lg:p-8 border-b border-border bg-muted/50">
                    <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                        <Zap class="w-6 h-6 text-primary" />
                        Executive Financial Tools
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 lg:p-8">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 lg:gap-6">
                        <Link
                            v-for="action in quickActions"
                            :key="action.title"
                            :href="action.href"
                            class="group relative p-6 rounded-[2rem] bg-muted border border-border transition-all duration-300 hover:border-emerald-500 hover:shadow-lg"
                        >
                            <div class="flex flex-col items-center text-center space-y-4">
                                <div class="w-14 h-14 rounded-2xl bg-card border border-border flex items-center justify-center shadow-inner group-hover:bg-emerald-600 group-hover:text-white group-hover:border-emerald-600 transition-all">
                                    <component :is="action.icon" class="w-7 h-7" />
                                </div>
                                <div>
                                    <h3 class="text-xs font-black text-foreground uppercase tracking-widest leading-none mb-1">{{ action.title }}</h3>
                                    <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest opacity-60 line-clamp-1">{{ action.description }}</p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </CardContent>
            </Card>

            <!-- Main Intelligence Hub -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Transaction Ledger -->
                <div class="lg:col-span-2 space-y-6 lg:space-y-8">
                    <Card class="bg-card shadow-sm border-border rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-6 lg:p-8 border-b border-border">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                                    <Receipt class="w-6 h-6 text-primary" />
                                    Transaction Ledger
                                </CardTitle>
                                <div class="flex gap-2">
                                    <Button variant="ghost" size="sm" class="rounded-xl font-black text-[10px] uppercase tracking-widest text-muted-foreground border border-border">
                                        <Filter class="w-4 h-4 mr-2" /> Filter
                                    </Button>
                                    <Button variant="ghost" size="sm" class="rounded-xl font-black text-[10px] uppercase tracking-widest text-muted-foreground border border-border">
                                        View All <ChevronRight class="w-4 h-4 ml-1" />
                                    </Button>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent class="p-4 lg:p-8">
                            <div class="space-y-4">
                                <div v-for="(transaction, index) in recentTransactions" :key="index" class="flex items-center gap-4 lg:gap-6 p-4 lg:p-5 rounded-2xl lg:rounded-[2rem] bg-muted/50 border border-border group transition-all lg:hover:bg-card lg:hover:shadow-md">
                                    <div :class="[
                                        'w-10 h-10 lg:w-12 lg:h-12 rounded-xl lg:rounded-2xl flex items-center justify-center font-bold shadow-inner shrink-0 lg:group-hover:scale-110 transition-transform',
                                        transaction.type === 'income' ? 'bg-emerald-50 text-emerald-600' : 
                                        transaction.type === 'expense' ? 'bg-rose-50 text-rose-600' : 
                                        'bg-blue-50 text-blue-600'
                                    ]">
                                        <component :is="transaction.type === 'income' ? TrendingUp : transaction.type === 'expense' ? TrendingDown : Receipt" class="w-5 h-5 lg:w-6 lg:h-6" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center justify-between gap-1 mb-1">
                                            <h4 class="text-base lg:text-lg font-black text-foreground tracking-tighter truncate">{{ transaction.title }}</h4>
                                            <Badge :class="[
                                                'font-black text-[10px] uppercase h-6 px-3 border-0',
                                                transaction.type === 'income' ? 'bg-emerald-50 text-emerald-600' : 
                                                transaction.type === 'expense' ? 'bg-rose-50 text-rose-600' : 
                                                'bg-blue-50 text-blue-600'
                                            ]">
                                                {{ transaction.type === 'income' ? '+' : transaction.type === 'expense' ? '-' : '' }}UGX {{ (transaction.amount / 1000).toFixed(0) }}K
                                            </Badge>
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                                            <p class="text-xs font-bold text-muted-foreground uppercase tracking-wide truncate">{{ transaction.description }}</p>
                                            <div class="flex items-center gap-3 text-[10px] font-black uppercase text-muted-foreground/80">
                                                <span class="flex items-center gap-1"><Clock class="w-3 h-3" /> {{ transaction.time }}</span>
                                                <Badge variant="outline" class="font-black text-[8px] h-5 px-2 border-border uppercase">{{ transaction.status }}</Badge>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Revenue Intelligence Grid -->
                    <Card class="bg-card shadow-sm border-border rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-8 border-b border-border bg-muted/50">
                            <CardTitle class="text-xl font-black tracking-tighter flex items-center gap-3 text-foreground/80">
                                <PieChart class="w-5 h-5" />
                                Visualized Revenue Intelligence
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8">
                            <div class="h-64 rounded-[2.5rem] bg-muted border-2 border-dashed border-border flex items-center justify-center p-8">
                                <div class="text-center group">
                                    <BarChart3 class="w-16 h-16 text-emerald-200 group-hover:scale-110 transition-transform mx-auto mb-4" />
                                    <p class="text-[10px] font-black text-muted-foreground uppercase tracking-widest mb-6">Aggregate Institutional Performance</p>
                                    <div class="flex flex-wrap items-center justify-center gap-8">
                                        <div class="flex items-center gap-3">
                                            <div class="w-4 h-4 bg-emerald-500 rounded-lg shadow-sm"></div>
                                            <span class="text-xs font-black text-muted-foreground uppercase">Rev: {{ formatCurrency(financialSummary.totalRevenue) }}</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-4 h-4 bg-rose-500 rounded-lg shadow-sm"></div>
                                            <span class="text-xs font-black text-muted-foreground uppercase">Exp: {{ formatCurrency(financialSummary.totalExpenses) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Strategic Insights Sidebar -->
                <div class="space-y-8">
                    <!-- Delinquent Accounts -->
                    <Card class="bg-card shadow-sm border-border rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-6 lg:p-8 border-b border-border">
                            <CardTitle class="text-xl font-black tracking-tighter flex items-center gap-3">
                                <CreditCard class="w-5 h-5 text-orange-500" />
                                Pending Receivables
                                <Badge class="bg-orange-500 text-white border-0 font-black text-[10px]">{{ pendingPayments.length }}</Badge>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-6 lg:p-8">
                            <div class="space-y-4">
                                <div v-for="payment in pendingPayments" :key="payment.name" class="p-5 rounded-3xl lg:rounded-[2rem] bg-orange-50/50 dark:bg-orange-900/10 border border-orange-100 dark:border-orange-900/20 group lg:hover:border-orange-500 transition-all">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h4 class="text-base font-black text-foreground leading-none mb-1 tracking-tight">{{ payment.name }}</h4>
                                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">{{ payment.class }}</p>
                                        </div>
                                        <Badge class="bg-card text-orange-600 border border-orange-100 dark:border-orange-800 font-black text-[9px]">
                                            UGX {{ (payment.amount / 1000).toFixed(0) }}K
                                        </Badge>
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] font-black uppercase text-muted-foreground/80">
                                        <Calendar class="w-3 h-3 text-orange-500" />
                                        Ex-Deadline: {{ payment.dueDate }}
                                    </div>
                                </div>
                                <Button variant="outline" class="w-full h-12 rounded-2xl border-border font-black text-[10px] uppercase tracking-widest hover:bg-primary hover:text-white hover:border-primary">
                                    <Plus class="w-4 h-4 mr-2" /> Dispatch Reminders
                                </Button>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Strategic Summary -->
                    <Card class="bg-card shadow-sm border-border rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-6 lg:p-8 border-b border-border">
                            <CardTitle class="text-xl font-black tracking-tighter flex items-center gap-3 text-foreground/80">
                                <BarChart3 class="w-5 h-5" />
                                Strategic Overview
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-6 lg:p-8">
                            <div class="space-y-6">
                                <div v-for="(val, label) in {
                                    'Institutional Population': stats?.students_count || '0 Active',
                                    'Fee Collection Rate': '84% (Target: 95%)',
                                    'Operational Margin': financialSummary.profitMargin,
                                    'Monthly Growth Delta': financialSummary.monthlyGrowth
                                }" :key="label" class="flex flex-col sm:flex-row sm:items-center justify-between py-1 group gap-1">
                                    <span class="text-[11px] font-black text-muted-foreground uppercase tracking-widest lg:group-hover:text-primary transition-colors">{{ label }}</span>
                                    <span class="text-sm font-black text-foreground tracking-tight">{{ val }}</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Annual Audit Action -->
                    <div class="bg-foreground dark:bg-muted rounded-3xl lg:rounded-[3rem] p-8 lg:p-10 text-background dark:text-foreground shadow-2xl relative overflow-hidden group">
                        <div class="relative z-10 text-center space-y-6">
                            <div class="w-16 h-16 lg:w-20 lg:h-20 bg-background/10 rounded-2xl lg:rounded-[2rem] flex items-center justify-center mx-auto border border-background/20 lg:group-hover:scale-110 transition-transform">
                                <Download class="w-8 h-8 lg:w-10 lg:h-10 text-emerald-400" />
                            </div>
                            <div>
                                <h3 class="text-xl lg:text-2xl font-black tracking-tighter leading-none mb-3">Institutional Audit</h3>
                                <p class="text-muted-foreground text-xs font-bold uppercase tracking-wide leading-relaxed">
                                    Generate comprehensive monthly fiscal analytics for stakeholders.
                                </p>
                            </div>
                            <Button class="w-full bg-primary hover:bg-primary/90 text-primary-foreground font-black rounded-2xl h-12 text-[10px] uppercase tracking-widest border-0 shadow-lg shadow-primary/20">
                                <Download class="w-4 h-4 mr-2" /> Download Fiscal Intelligence
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Sidebar>
</template>