<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
    ArrowLeft, 
    Bus, 
    MapPin, 
    Users, 
    Phone, 
    Calendar, 
    AlertTriangle, 
    Clock, 
    Navigation, 
    User, 
    MoreVertical,
    Activity,
    ShieldCheck,
    Map,
    Award
} from 'lucide-vue-next';

const props = defineProps({
    route: Object
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRoles = computed(() => user.value?.roles || []);
const isAdminOrStaff = computed(() => userRoles.value.some(r => ['admin', 'staff'].includes(r)));

const getStatusColor = (status) => {
    switch (status?.toLowerCase()) {
        case 'active': return 'text-emerald-600 bg-emerald-500/10 border-emerald-500/20';
        case 'maintenance': return 'text-amber-600 bg-amber-500/10 border-amber-500/20';
        default: return 'text-muted-foreground bg-muted0/10 border-muted-foreground/20';
    }
};

const getInitials = (name) => {
    return name?.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2) || 'DV';
};
</script>

<template>
    <Head :title="route.name + ' - Transport Intelligence'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <Link :href="route('transport.index')">
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-white/10 dark:bg-white/5">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Infrastructure
                    </Button>
                </Link>
                <span class="text-warm-muted text-dark-muted">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted text-dark-muted">Logistics Detail</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Professional Header Section -->
            <div class="relative overflow-hidden bg-white bg-dark-bg transition-colors shadow-sm rounded-[3rem] border border-terracotta/20 p-8 md:p-12">
                <div class="relative z-10 flex flex-col md:flex-row md:items-end md:justify-between gap-8">
                    <div class="flex flex-col md:flex-row items-center md:items-end gap-8">
                        <div class="w-24 h-24 bg-terracotta/5 rounded-[2.5rem] flex items-center justify-center border border-terracotta/20 shadow-inner relative group overflow-hidden">
                            <Bus class="w-12 h-12 text-blue-600 relative z-10" />
                        </div>
                        
                        <div class="text-center md:text-left space-y-4">
                            <div class="flex items-center justify-center md:justify-start gap-2">
                                <Badge :class="['rounded-full px-4 py-1 h-7 border-0 font-black uppercase text-[9px] tracking-widest', getStatusColor(route.status)]">
                                    {{ route.status || 'Active' }}
                                </Badge>
                                <span class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Route ID: #{{ route.id }}</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-warm-text text-dark-text tracking-tighter">
                                {{ route.name }}
                            </h1>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-6">
                                <div class="flex items-center text-sm font-bold text-warm-muted text-dark-muted uppercase tracking-wide">
                                    <MapPin class="w-5 h-5 mr-2 text-blue-600" />
                                    {{ route.description || 'Standard School Transit' }}
                                </div>
                                <div class="flex items-center text-sm font-bold text-warm-muted text-dark-muted uppercase tracking-wide">
                                    <ShieldCheck class="w-5 h-5 mr-2 text-emerald-600" />
                                    Transit Verified
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logistics Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div v-for="(stat, idx) in [
                    { label: 'Departure', val: route.departure_time, icon: Clock, color: 'blue' },
                    { label: 'Arrival', val: route.arrival_time, icon: Navigation, color: 'emerald' },
                    { label: 'Vehicle', val: route.vehicle_number || 'B-702', icon: Bus, color: 'orange' },
                    { label: 'Capacity', val: route.capacity || '45 Seats', icon: Users, color: 'indigo' }
                ]" :key="idx" class="bg-white bg-dark-bg p-6 rounded-[2.5rem] border border-terracotta/20 shadow-sm group hover:border-blue-500/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div :class="['w-12 h-12 rounded-2xl flex items-center justify-center transition-colors shadow-inner font-bold', `bg-${stat.color}-500/10 text-${stat.color}-600`]">
                            <component :is="stat.icon" class="w-6 h-6" />
                        </div>
                        <Badge class="bg-terracotta/5 border-0 text-warm-muted text-dark-muted font-black text-[9px] uppercase tracking-widest">{{ stat.label }}</Badge>
                    </div>
                    <h3 class="text-2xl font-black text-warm-text text-dark-text tracking-tighter leading-none">{{ stat.val }}</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Intelligence -->
                <div class="lg:col-span-2 space-y-8">
                    <Card class="bg-white bg-dark-bg transition-colors shadow-sm border-terracotta/20 rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-8 border-b border-terracotta/10 bg-terracotta/5">
                            <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                                <Map class="w-6 h-6 text-blue-600" />
                                Operational Route Mapping
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8 space-y-8">
                            <!-- Driver Mini-Profile -->
                            <div class="p-8 rounded-[2.5rem] bg-terracotta/5 border border-terracotta/10 shadow-sm group">
                                <div class="flex flex-col md:flex-row items-center gap-8">
                                    <div class="w-20 h-20 rounded-[2rem] bg-blue-600 flex items-center justify-center text-white font-black text-2xl shadow-lg">
                                        {{ getInitials(route.driver?.name) }}
                                    </div>
                                    <div class="flex-1 text-center md:text-left">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-warm-muted text-dark-muted mb-1">Transit Commander</p>
                                        <h3 class="text-2xl font-black text-warm-text text-dark-text tracking-tighter">{{ route.driver?.name || 'Assigned Driver' }}</h3>
                                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mt-2">
                                            <span class="flex items-center text-xs font-bold text-warm-muted text-dark-muted">
                                                <Phone class="w-3 h-3 mr-1 text-blue-600" />
                                                {{ route.driver?.phone || '+256-xxx-xxx' }}
                                            </span>
                                            <span class="flex items-center text-xs font-bold text-warm-muted text-dark-muted">
                                                <Award class="w-3 h-3 mr-1 text-emerald-600" />
                                                {{ route.driver?.experience || '12' }} Yrs Exp.
                                            </span>
                                        </div>
                                    </div>
                                    <Button variant="outline" class="rounded-xl h-10 px-6 font-black text-[10px] uppercase tracking-widest border-terracotta/20 text-warm-muted text-dark-muted">Contact</Button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-6 rounded-[2rem] bg-terracotta/5 border border-terracotta/10">
                                    <h4 class="text-xs font-black uppercase text-warm-muted text-dark-muted tracking-widest mb-4">Vehicle Specification</h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-[10px] font-bold text-warm-muted text-dark-muted uppercase">Class</span>
                                            <span class="text-sm font-black text-warm-text text-dark-text">{{ route.vehicle_type || 'Coach' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-[10px] font-bold text-warm-muted text-dark-muted uppercase">Identification</span>
                                            <span class="text-sm font-black text-warm-text text-dark-text">{{ route.vehicle_number || 'UAB 123X' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6 rounded-[2rem] bg-emerald-500/5 border border-emerald-500/10">
                                    <div class="flex items-center gap-4 mb-4">
                                        <Activity class="w-5 h-5 text-emerald-600" />
                                        <h4 class="text-xs font-black uppercase text-warm-muted text-dark-muted tracking-widest">Health & Safety</h4>
                                    </div>
                                    <p class="text-[10px] font-bold text-warm-muted text-dark-muted leading-relaxed italic">
                                        Vehicle underwent security and mechanical audit on Jan 12, 2026. Certified for student transit.
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Passenger Manifest -->
                    <Card class="bg-white bg-dark-bg transition-colors shadow-sm border-terracotta/20 rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-8 border-b border-terracotta/10">
                            <CardTitle class="text-xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                                <Users class="w-5 h-5" />
                                Verified Passenger Manifest
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8">
                            <div class="space-y-4">
                                <div v-for="n in 3" :key="n" class="flex items-center justify-between p-4 rounded-2xl bg-terracotta/5 border border-terracotta/10 group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                                            <User class="w-4 h-4" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-warm-text text-dark-text leading-none mb-1">Authenticated Student #{{ n }}</p>
                                            <p class="text-[9px] font-bold text-warm-muted text-dark-muted uppercase tracking-widest">Primary Pickup Point A</p>
                                        </div>
                                    </div>
                                    <Badge class="bg-emerald-500 text-white font-black text-[8px] h-5 rounded-lg border-0">ONBOARD</Badge>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Right Sidebar -->
                <div class="space-y-8">
                    <div class="bg-white bg-dark-bg transition-colors rounded-[3rem] border border-terracotta/20 p-8 shadow-sm">
                        <h3 class="text-xl font-black text-warm-text text-dark-text tracking-tighter mb-8 bg-terracotta/5 rounded-xl px-4 py-1 inline-block">Real-time Intel</h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-terracotta/5 border border-terracotta/10 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
                                        <AlertTriangle class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Alerts</p>
                                        <p class="text-sm font-black text-emerald-600">All Systems Nominal</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-8 rounded-[3rem] bg-terracotta transition-colors text-white shadow-lg relative overflow-hidden group">
                                <div class="relative z-10 text-center">
                                    <div class="w-16 h-16 bg-white/10 dark:bg-white/5 dark:bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-white/20 dark:border-white/10 dark:border-white/10">
                                        <Navigation class="w-8 h-8 text-white/80" />
                                    </div>
                                    <h4 class="text-xl font-black mb-2 tracking-tighter leading-none text-white">Live Tracking</h4>
                                    <p class="text-white/70 text-[10px] font-medium mb-6 leading-relaxed">
                                        Initialize geospatial satellite tracking for this transit unit.
                                    </p>
                                    <Button class="w-full bg-white text-terracotta hover:bg-white/90 font-black rounded-xl h-10 text-[10px] uppercase tracking-widest shadow-md border-0">
                                        Launch Radar
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <Link :href="route('transport.index')" class="block mt-8">
                            <Button variant="outline" class="w-full h-12 border-terracotta/20 text-warm-text text-dark-text hover:text-terracotta font-black rounded-2xl">
                                <ArrowLeft class="w-4 h-4 mr-2" />
                                System Back
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
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