<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
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
        ? 'text-forest bg-forest/10 border-forest/20' 
        : 'text-amber bg-amber/10 border-amber/20';
};
</script>

<template>
    <Head :title="fee.title + ' - Financial Intelligence'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <Link :href="route('fees.index')">
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-terracotta/10 text-warm-text text-dark-text">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Accounts
                    </Button>
                </Link>
                <span class="text-warm-muted text-dark-muted">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted text-dark-muted">Transaction Details</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Header -->
            <div class="relative overflow-hidden card-warm rounded-[3rem] p-8 md:p-12">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex items-center gap-8">
                        <div class="w-24 h-24 bg-terracotta/5 rounded-[2rem] flex items-center justify-center border border-terracotta/20 shadow-inner">
                            <DollarSign class="w-12 h-12 text-forest" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <Badge :class="['rounded-lg px-3 py-1 text-[10px] font-black uppercase tracking-widest border-0', getStatusColor(fee.status)]">
                                    {{ fee.status || 'Active' }}
                                </Badge>
                                <span class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Type: {{ fee.type || 'Academic' }}</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-warm-text text-dark-text mb-2 tracking-tighter">
                                {{ fee.title }}
                            </h1>
                            <p class="text-lg text-warm-muted text-dark-muted font-medium">
                                Financial structure and billing cycle specifications.
                            </p>
                        </div>
                    </div>

                    <div v-if="isAdminOrTeacher" class="flex flex-wrap items-center gap-3">
                        <Link :href="route('fees.edit', fee.id)">
                            <Button class="accent-terracotta text-white font-black rounded-2xl h-14 px-8 shadow-md shadow-terracotta/30">
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
                    <div class="card-warm rounded-[3rem] overflow-hidden">
                        <div class="p-8 border-b border-terracotta/20 bg-terracotta/5">
                            <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                                <CreditCard class="w-6 h-6 text-forest" />
                                Financial Breakdown
                            </h3>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                                <!-- Student info block -->
                                <div class="p-6 rounded-[2rem] bg-terracotta/5 border border-terracotta/20 group">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-10 h-10 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta group-hover:scale-110 transition-transform">
                                            <User class="w-5 h-5" />
                                        </div>
                                        <h4 class="font-black text-warm-text text-dark-text uppercase tracking-tighter">Student</h4>
                                    </div>
                                    <p class="text-xl font-black text-warm-text text-dark-text">
                                        {{ fee.student?.user?.name || 'Unassigned' }}
                                    </p>
                                    <p class="text-xs font-bold text-warm-muted text-dark-muted mt-1 uppercase tracking-wider">
                                        {{ fee.student?.academic_class?.name || 'Class Unknown' }}
                                    </p>
                                </div>

                                <!-- Verification Status -->
                                <div :class="['p-6 rounded-[2rem] border transition-all duration-500', fee.status === 'paid' ? 'bg-forest/10 border-forest/20' : 'bg-amber/10 border-amber/20']">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div :class="['w-10 h-10 rounded-xl flex items-center justify-center', fee.status === 'paid' ? 'bg-forest/20 text-forest' : 'bg-amber/20 text-amber']">
                                            <ShieldCheck v-if="fee.status === 'paid'" class="w-5 h-5" />
                                            <Clock v-else class="w-5 h-5" />
                                        </div>
                                        <h4 :class="['font-black uppercase tracking-tighter', fee.status === 'paid' ? 'text-forest' : 'text-amber']">
                                            Verification Status
                                        </h4>
                                    </div>
                                    <p :class="['text-sm font-bold', fee.status === 'paid' ? 'text-warm-text text-dark-text' : 'text-warm-text text-dark-text']">
                                        {{ fee.status === 'paid' ? 'This transaction is fully cleared and verified by the institute audit department.' : 'Awaiting payment confirmation. Please settle the outstanding balance by the maturity date.' }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Transaction History -->
                    <div class="card-warm rounded-[3rem] overflow-hidden">
                        <div class="p-8 border-b border-terracotta/20">
                            <h3 class="text-xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                                <History class="w-5 h-5 text-terracotta" />
                                Internal Transaction History
                            </h3>
                        </div>
                        <div class="p-8">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-lg bg-forest/20 flex items-center justify-center text-forest">
                                            <CheckCircle2 class="w-4 h-4" />
                                        </div>
                                        <p class="text-sm font-bold text-warm-text text-dark-text">Record Initialized</p>
                                    </div>
                                    <span class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">
                                        {{ new Date(fee.created_at).toLocaleDateString() }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-lg bg-terracotta/20 flex items-center justify-center text-terracotta">
                                            <Edit class="w-4 h-4" />
                                        </div>
                                        <p class="text-sm font-bold text-warm-text text-dark-text">Last System Audit</p>
                                    </div>
                                    <span class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">
                                        {{ new Date(fee.updated_at).toLocaleDateString() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- end lg:col-span-2 -->

                <!-- Right Sidebar -->
                <div class="space-y-8">
                    <div class="card-warm rounded-[3rem] p-8">
                        <h3 class="text-xl font-black text-warm-text text-dark-text tracking-tighter mb-8 bg-terracotta/5 rounded-xl px-4 py-1 inline-block">Payment Intel</h3>

                        <div class="space-y-6">
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-forest/10 flex items-center justify-center text-forest group-hover:scale-110 transition-transform">
                                        <DollarSign class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Type</p>
                                        <p class="text-sm font-black text-warm-text text-dark-text">{{ fee.fee_type || fee.type || 'Standard' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div v-if="fee.status !== 'paid'" class="p-6 rounded-3xl bg-amber/5 border border-amber/20">
                                <div class="flex items-center gap-3 mb-3 text-amber">
                                    <AlertCircle class="w-5 h-5" />
                                    <h4 class="font-black uppercase tracking-tighter">Action Required</h4>
                                </div>
                                <p class="text-xs font-bold text-warm-muted text-dark-muted leading-relaxed mb-4">
                                    This payment is currently outstanding. Please secure funds and clear via bank transfer or portal.
                                </p>
                                <Button class="w-full h-10 bg-amber hover:bg-amber/90 text-white font-black rounded-xl text-xs shadow-md">
                                    Secure Payment Link
                                </Button>
                            </div>
                        </div>

                        <Link :href="route('fees.index')" class="block mt-8">
                            <Button variant="outline" class="w-full h-12 border-terracotta/20 text-warm-muted text-dark-muted hover:text-terracotta hover:bg-terracotta/5 font-black rounded-2xl">
                                <ArrowLeft class="w-4 h-4 mr-2" />
                                Back to Accounts
                            </Button>
                        </Link>
                    </div>

                    <!-- Digital Receipt Access -->
                    <div class="rounded-[3rem] accent-terracotta p-8 text-white shadow-2xl shadow-terracotta/30 relative overflow-hidden group">
                        <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-white/5 blur-[40px] rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
                        <div class="relative z-10 text-center">
                            <div class="w-20 h-20 bg-white/10 dark:bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-white/20 dark:border-white/10">
                                <Receipt class="w-10 h-10 text-white" />
                            </div>
                            <h4 class="text-2xl font-black mb-2 tracking-tighter">e-Receipt</h4>
                            <p class="text-white/70 text-sm font-medium mb-8 leading-relaxed">
                                Download your verified digital transaction receipt for your records.
                            </p>
                            <Button class="w-full bg-white text-terracotta hover:bg-white/90 font-black rounded-2xl h-12 shadow-2xl">
                                Download PDF
                            </Button>
                        </div>
                    </div>
                </div><!-- end sidebar -->

            </div><!-- end grid -->
        </div><!-- end space-y-8 -->
    </Sidebar>
</template>