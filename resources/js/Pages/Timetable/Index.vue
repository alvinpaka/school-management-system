<script setup>
import { Head, usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { 
    Calendar,
    Clock,
    BookOpen,
    Users,
    MapPin,
    CheckCircle,
    Plus,
    Filter,
    Download,
    Coffee,
    MoreHorizontal,
    Monitor,
    Music,
    Globe,
    Atom,
    Palette
} from 'lucide-vue-next';

const props = defineProps({
    timetable: Array,
    classes: Array
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRoles = computed(() => user.value?.roles || []);
const isAdminOrTeacher = computed(() => userRoles.value.some(r => ['admin', 'teacher'].includes(r)));

const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
const timeSlots = [
    '08:00 AM', '09:00 AM', '10:00 AM', '11:00 AM', 
    '12:00 PM', '01:00 PM', '02:00 PM', '03:00 PM'
];

const getSubjectIcon = (name) => {
    const n = name?.toLowerCase() || '';
    if (n.includes('math')) return Monitor;
    if (n.includes('science')) return Atom;
    if (n.includes('music')) return Music;
    if (n.includes('art')) return Palette;
    if (n.includes('english')) return Globe;
    return BookOpen;
};

const getSubjectColor = (name) => {
    const n = name?.toLowerCase() || '';
    if (n.includes('math')) return 'text-terracotta bg-terracotta/10 border-terracotta/20';
    if (n.includes('science')) return 'text-forest bg-forest/10 border-forest/20';
    if (n.includes('music')) return 'text-destructive bg-destructive/10 border-destructive/20';
    if (n.includes('art')) return 'text-amber bg-amber/10 border-amber/20';
    return 'text-terracotta bg-terracotta/10 border-terracotta/20';
};
</script>

<template>
    <Head :title="isAdminOrTeacher ? 'Schedule Management | EduManage Pro' : 'My Daily Routine | EduManage Pro'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-terracotta/10 rounded-lg">
                    <Calendar class="w-4 h-4 text-terracotta" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted text-dark-muted">
                    {{ isAdminOrTeacher ? 'Registry Control' : 'My Routine' }}
                </span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Dramatic Header Section -->
            <div class="relative overflow-hidden card-warm rounded-[2.5rem] p-8 md:p-12">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-terracotta/5 blur-[80px] rounded-full animate-float"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-forest/5 blur-[80px] rounded-full animate-float" style="animation-delay: 2s"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-black text-warm-text text-dark-text mb-2 tracking-tighter">
                            {{ isAdminOrTeacher ? 'Master Timetable' : 'Daily Routine' }}
                        </h1>
                        <p class="text-lg text-warm-muted text-dark-muted font-medium">
                            {{ isAdminOrTeacher 
                                ? 'Coordinate academic sessions, room allocations, and faculty schedules.' 
                                : 'Track your lecture times and academic commitments across the week.' }}
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        <Button variant="outline" class="h-14 bg-white bg-dark-bg border border-terracotta/20 shadow-sm rounded-2xl px-6 font-bold gap-2 text-warm-muted text-dark-muted hover:text-terracotta">
                            <Download class="w-5 h-5" />
                            Export PDF
                        </Button>
                        <Button v-if="isAdminOrTeacher" class="accent-terracotta text-white font-black rounded-2xl h-14 px-8 shadow-xl shadow-terracotta/30">
                            <Plus class="w-5 h-5 mr-2" />
                            New Session
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="card-warm p-6 rounded-[2rem] group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                            <Clock class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-warm-text text-dark-text">32h</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Total Weekly Hours</p>
                </div>
                
                <div class="card-warm p-6 rounded-[2rem] group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-forest/10 flex items-center justify-center text-forest">
                            <Coffee class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-forest">05</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Break Segments</p>
                </div>

                <div class="card-warm p-6 rounded-[2rem] group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber/10 flex items-center justify-center text-amber">
                            <MapPin class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-amber">12</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Assigned Rooms</p>
                </div>

                <div class="card-warm p-6 rounded-[2rem] group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                            <Users class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-warm-text text-dark-text">08</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Faculty Members</p>
                </div>
            </div>

            <!-- Weekly Schedule Grid -->
            <div class="card-warm rounded-[3rem] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-terracotta/5">
                                <th class="p-6 text-left border-b-2 border-terracotta/20 border-r border-terracotta/10 w-36">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Time</span>
                                        <span class="text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest mt-1">→ Day</span>
                                    </div>
                                </th>
                                <th v-for="day in days" :key="day" class="p-6 text-center border-b-2 border-terracotta/20">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-black text-warm-text text-dark-text">{{ day }}</span>
                                        <span class="text-[9px] font-bold text-terracotta mt-1 uppercase tracking-wider">Week {{ Math.floor(Math.random() * 4) + 1 }}</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(time, timeIndex) in timeSlots" :key="time" class="group transition-colors duration-200 hover:bg-terracotta/5">
                                <td class="p-6 text-left border-r border-terracotta/10 bg-terracotta/5 sticky left-0 z-10">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-black text-warm-text text-dark-text">{{ time }}</span>
                                        <span class="text-[9px] font-medium text-terracotta mt-0.5">{{ ['Breakfast', 'Morning', 'Lunch', 'Afternoon'][Math.floor(Math.random() * 4)] }}</span>
                                    </div>
                                </td>

                                <td v-for="(day, dayIndex) in days" :key="day" class="p-3 min-w-[200px] align-top">
                                    <!-- Dynamic Schedule Block Placeholder -->
                                    <div v-if="day === 'Monday' && time === '08:00 AM'" 
                                         class="group/card relative overflow-hidden bg-gradient-to-br from-terracotta/5 to-terracotta/10 border border-terracotta/20 shadow-sm rounded-xl p-4 hover:shadow-md hover:shadow-terracotta/10 transition-all duration-300 hover:-translate-y-0.5">
                                        <div class="absolute top-0 right-0 w-16 h-16 bg-terracotta/5 rounded-full -mr-8 -mt-8 group-hover/card:scale-150 transition-transform duration-500"></div>
                                        <div class="relative z-10">
                                            <div class="flex items-start gap-3 mb-3">
                                                <div class="w-10 h-10 rounded-xl bg-terracotta/15 flex items-center justify-center text-terracotta shadow-sm flex-shrink-0">
                                                    <Monitor class="w-5 h-5" />
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-black text-warm-text text-dark-text tracking-tight truncate">Advanced Mathematics</p>
                                                    <p class="text-[10px] font-bold text-terracotta/70 uppercase tracking-widest mt-0.5 flex items-center gap-1">
                                                        <MapPin class="w-3 h-3" />
                                                        Room 201
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between pt-2 border-t border-terracotta/10">
                                                <Badge class="bg-terracotta/10 text-terracotta border-0 font-black text-[9px] h-5 rounded-md">
                                                    <Clock class="w-3 h-3 inline mr-1" />
                                                    8-9 AM
                                                </Badge>
                                                <div class="flex items-center gap-1.5">
                                                    <div class="w-5 h-5 rounded-full bg-terracotta/15 flex items-center justify-center">
                                                        <span class="text-[9px] font-black text-terracotta">JD</span>
                                                    </div>
                                                    <span class="text-[10px] font-bold text-terracotta">John. D</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-else-if="day === 'Tuesday' && time === '10:00 AM'" 
                                         class="group/card relative overflow-hidden bg-gradient-to-br from-forest/5 to-forest/10 border border-forest/20 shadow-sm rounded-xl p-4 hover:shadow-md hover:shadow-forest/10 transition-all duration-300 hover:-translate-y-0.5">
                                        <div class="absolute top-0 right-0 w-16 h-16 bg-forest/5 rounded-full -mr-8 -mt-8 group-hover/card:scale-150 transition-transform duration-500"></div>
                                        <div class="relative z-10">
                                            <div class="flex items-start gap-3 mb-3">
                                                <div class="w-10 h-10 rounded-xl bg-forest/15 flex items-center justify-center text-forest shadow-sm flex-shrink-0">
                                                    <Atom class="w-5 h-5" />
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-black text-warm-text text-dark-text tracking-tight truncate">Quantum Physics</p>
                                                    <p class="text-[10px] font-bold text-forest/70 uppercase tracking-widest mt-0.5 flex items-center gap-1">
                                                        <MapPin class="w-3 h-3" />
                                                        Lab 101
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between pt-2 border-t border-forest/10">
                                                <Badge class="bg-forest/10 text-forest border-0 font-black text-[9px] h-5 rounded-md">
                                                    <Clock class="w-3 h-3 inline mr-1" />
                                                    10-11 AM
                                                </Badge>
                                                <div class="flex items-center gap-1.5">
                                                    <div class="w-5 h-5 rounded-full bg-forest/15 flex items-center justify-center">
                                                        <span class="text-[9px] font-black text-forest">SK</span>
                                                    </div>
                                                    <span class="text-[10px] font-bold text-forest">Sarah. K</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-else-if="day === 'Wednesday' && time === '01:00 PM'" 
                                         class="group/card relative overflow-hidden bg-gradient-to-br from-amber/5 to-amber/10 border border-amber/20 shadow-sm rounded-xl p-4 hover:shadow-md hover:shadow-amber/10 transition-all duration-300 hover:-translate-y-0.5">
                                        <div class="absolute top-0 right-0 w-16 h-16 bg-amber/5 rounded-full -mr-8 -mt-8 group-hover/card:scale-150 transition-transform duration-500"></div>
                                        <div class="relative z-10">
                                            <div class="flex items-start gap-3 mb-3">
                                                <div class="w-10 h-10 rounded-xl bg-amber/15 flex items-center justify-center text-amber shadow-sm flex-shrink-0">
                                                    <Palette class="w-5 h-5" />
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-black text-warm-text text-dark-text tracking-tight truncate">Creative Arts</p>
                                                    <p class="text-[10px] font-bold text-amber/70 uppercase tracking-widest mt-0.5 flex items-center gap-1">
                                                        <MapPin class="w-3 h-3" />
                                                        Studio 3
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between pt-2 border-t border-amber/10">
                                                <Badge class="bg-amber/10 text-amber border-0 font-black text-[9px] h-5 rounded-md">
                                                    <Clock class="w-3 h-3 inline mr-1" />
                                                    1-2 PM
                                                </Badge>
                                                <div class="flex items-center gap-1.5">
                                                    <div class="w-5 h-5 rounded-full bg-amber/15 flex items-center justify-center">
                                                        <span class="text-[9px] font-black text-amber">ML</span>
                                                    </div>
                                                    <span class="text-[10px] font-bold text-amber">Mike. L</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Lunch Break Special -->
                                    <div v-else-if="time === '12:00 PM' && (day === 'Monday' || day === 'Wednesday' || day === 'Friday')" 
                                         class="bg-gradient-to-br from-amber/5 to-orange-50 dark:to-orange-950/10 border border-amber/20 rounded-xl p-4 text-center group-hover:bg-amber/5 transition-colors">
                                        <div class="flex flex-col items-center gap-2">
                                            <Coffee class="w-8 h-8 text-amber/40" />
                                            <p class="text-[10px] font-black text-amber/60 uppercase tracking-widest">Lunch Break</p>
                                            <p class="text-[8px] text-amber/40">12:00 - 1:00 PM</p>
                                        </div>
                                    </div>

                                    <!-- Empty State Cell -->
                                    <div v-else class="relative group/empty">
                                        <div class="h-28 rounded-xl border-2 border-dashed border-terracotta/15 bg-terracotta/5 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 group-hover:border-terracotta/30 group-hover:bg-terracotta/10">
                                            <Plus class="w-6 h-6 text-terracotta/40 mb-1" />
                                            <span class="text-[8px] font-bold text-terracotta/40 uppercase tracking-wider">Add Session</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Legend -->
            <div class="flex flex-wrap items-center justify-center gap-6 py-6 border-t border-terracotta/20">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-terracotta"></div>
                    <span class="text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Mathematics</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-forest"></div>
                    <span class="text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Science</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-destructive"></div>
                    <span class="text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Language</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-amber"></div>
                    <span class="text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Arts</span>
                </div>
            </div>
        </div>
    </Sidebar>
</template>
<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(196, 98, 45, 0.05);
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(196, 98, 45, 0.3);
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(196, 98, 45, 0.5);
}

/* Sticky column effect */
.sticky {
    position: sticky;
    background: inherit;
}

/* Smooth transitions */
.group\/card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hover effects */
.group\/empty {
    transition: all 0.2s ease;
}
</style>