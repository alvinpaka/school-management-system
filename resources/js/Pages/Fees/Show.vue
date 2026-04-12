<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
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
    Trash2,
    CreditCard,
    Receipt,
    History,
    MoreVertical,
    CheckCircle2,
    ShieldCheck
} from 'lucide-vue-next';

const props = defineProps({
    fee: {
        type: Object,
        required: true
    }
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRoles = computed(() => user.value?.roles || []);
const isAdminOrTeacher = computed(() => userRoles.value.some(r => ['admin', 'teacher'].includes(r)));

const deleteFee = () => {
    if (confirm('Are you sure you want to delete this fee?')) {
        router.delete(route('fees.destroy', props.fee.id));
    }
};

const getStatusColor = (status) => {
    return status === 'paid' 
        ? 'text-emerald-600 bg-emerald-500/10 border-emerald-500/20' 
        : 'text-amber-600 bg-amber-500/10 border-amber-500/20';
};
</script>

<template>
    <Head :title="fee.title + ' - Financial Intelligence'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <Link :href="route('fees.index')">
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-white/10">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Accounts
                    </Button>
                </Link>
                <span class="text-gray-400">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-gray-500">Transaction Details</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Header -->
            <div class="relative overflow-hidden bg-white dark:bg-slate-900 shadow-sm rounded-[3rem] border border-slate-200 dark:border-slate-800 p-8 md:p-12">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex items-center gap-8">
                        <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-[2rem] flex items-center justify-center border border-slate-200 dark:border-slate-700 shadow-inner">
                            <DollarSign class="w-12 h-12 text-emerald-600" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <Badge :class="['rounded-lg px-3 py-1 text-[10px] font-black uppercase tracking-widest border-0', getStatusColor(fee.status)]">
                                    {{ fee.status || 'Active' }}
                                </Badge>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Type: {{ fee.type || 'Academic' }}</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-2 tracking-tighter">
                                {{ fee.title }}
                            </h1>
                            <p class="text-lg text-gray-500 dark:text-gray-400 font-medium">
                                Financial structure and billing cycle specifications.
                            </p>
                        </div>
                    </div>

                    <!-- ✅ isAdmin → isAdminOrTeacher -->
                    <div v-if="isAdminOrTeacher" class="flex flex-wrap items-center gap-3">
                        <Link :href="route('fees.edit', fee.id)">
                            <Button class="bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl h-14 px-8 shadow-md">
                                <Edit class="w-5 h-5 mr-2" />
                                Edit Schedule
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Financial Breakdown Card -->
                    <Card class="bg-white dark:bg-slate-900 shadow-sm border-slate-200 dark:border-slate-800 rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-8 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                            <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                                <CreditCard class="w-6 h-6 text-emerald-600" />
                                Financial Breakdown
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                                <!-- ✅ Student info block — now has its opening div -->
                                <div class="p-6 rounded-[2rem] bg-gray-50/50 dark:bg-white/5 border border-white/10 group">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                                            <User class="w-5 h-5" />
                                        </div>
                                        <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-tighter">Student</h4>
                                    </div>
                                    <p class="text-xl font-black text-gray-900 dark:text-white">
                                        {{ fee.student?.user?.name || 'Unassigned' }}
                                    </p>
                                    <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-wider">
                                        {{ fee.student?.academic_class?.name || 'Class Unknown' }}
                                    </p>
                                </div>

                                <!-- Verification Status -->
                                <div :class="['p-6 rounded-[2rem] border transition-all duration-500', fee.status === 'paid' ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-500/20 border-emerald-500' : 'bg-amber-500 text-white shadow-xl shadow-amber-500/20 border-amber-500']">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                            <ShieldCheck v-if="fee.status === 'paid'" class="w-5 h-5" />
                                            <Clock v-else class="w-5 h-5" />
                                        </div>
                                        <h4 class="font-black uppercase tracking-tighter">Verification Status</h4>
                                    </div>
                                    <p class="text-sm font-bold opacity-90">
                                        {{ fee.status === 'paid' ? 'This transaction is fully cleared and verified by the institute audit department.' : 'Awaiting payment confirmation. Please settle the outstanding balance by the maturity date.' }}
                                    </p>
                                </div>

                            </div>
                        </CardContent>
                    </Card>

                    <!-- Transaction History -->
                    <Card class="glass shadow-2xl border-white/10 rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-8 border-b border-white/5">
                            <CardTitle class="text-xl font-black tracking-tighter flex items-center gap-3 text-gray-700">
                                <History class="w-5 h-5" />
                                Internal Transaction History
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 dark:bg-white/5 border border-white/10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-600">
                                            <CheckCircle2 class="w-4 h-4" />
                                        </div>
                                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Record Initialized</p>
                                    </div>
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        {{ new Date(fee.created_at).toLocaleDateString() }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 dark:bg-white/5 border border-white/10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-600">
                                            <Edit class="w-4 h-4" />
                                        </div>
                                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Last System Audit</p>
                                    </div>
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        {{ new Date(fee.updated_at).toLocaleDateString() }}
                                    </span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                </div><!-- end lg:col-span-2 -->

                <!-- Right Sidebar -->
                <div class="space-y-8">
                    <div class="glass-card rounded-[3rem] border-white/10 p-8">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tighter mb-8 bg-emerald-500/10 rounded-xl px-4 py-1 inline-block">Payment Intel</h3>

                        <div class="space-y-6">
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/10 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                                        <DollarSign class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</p>
                                        <p class="text-sm font-black text-gray-900 dark:text-white">{{ fee.fee_type || fee.type || 'Standard' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div v-if="fee.status !== 'paid'" class="p-6 rounded-3xl bg-amber-500/10 border border-amber-500/20">
                                <div class="flex items-center gap-3 mb-3 text-amber-600">
                                    <AlertCircle class="w-5 h-5" />
                                    <h4 class="font-black uppercase tracking-tighter">Action Required</h4>
                                </div>
                                <p class="text-xs font-bold text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
                                    This payment is currently outstanding. Please secure funds and clear via bank transfer or portal.
                                </p>
                                <Button class="w-full h-10 bg-amber-600 hover:bg-amber-700 text-white font-black rounded-xl text-xs">
                                    Secure Payment Link
                                </Button>
                            </div>
                        </div>

                        <Link :href="route('fees.index')" class="block mt-8">
                            <Button variant="outline" class="w-full h-12 glass border-white/10 text-gray-700 hover:text-emerald-600 hover:bg-white/50 font-black rounded-2xl">
                                <ArrowLeft class="w-4 h-4 mr-2" />
                                Back to Accounts
                            </Button>
                        </Link>
                    </div>

                    <!-- Digital Receipt Access -->
                    <div class="rounded-[3rem] bg-gradient-to-br from-gray-900 to-slate-800 p-8 text-white shadow-2xl relative overflow-hidden group">
                        <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-white/5 blur-[40px] rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
                        <div class="relative z-10 text-center">
                            <div class="w-20 h-20 bg-white/10 rounded-3xl flex items-center justify-center mx-auto mb-6 backdrop-blur-xl border border-white/20">
                                <Receipt class="w-10 h-10 text-emerald-400" />
                            </div>
                            <h4 class="text-2xl font-black mb-2 tracking-tighter">e-Receipt</h4>
                            <p class="text-gray-400 text-sm font-medium mb-8 leading-relaxed">
                                Download your verified digital transaction receipt for your records.
                            </p>
                            <Button class="w-full bg-white text-gray-900 hover:bg-white/90 font-black rounded-2xl h-12 shadow-2xl">
                                Download PDF
                            </Button>
                        </div>
                    </div>
                </div><!-- end sidebar -->

            </div><!-- end grid -->
        </div><!-- end space-y-8 -->
    </Sidebar>
</template>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out forwards;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>