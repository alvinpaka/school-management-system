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
    timetable: Array, // Expected to be structured by day
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
    if (n.includes('math')) return 'text-blue-600 bg-blue-500/10 border-blue-500/20';
    if (n.includes('science')) return 'text-emerald-600 bg-emerald-500/10 border-emerald-500/20';
    if (n.includes('music')) return 'text-rose-600 bg-rose-500/10 border-rose-500/20';
    if (n.includes('art')) return 'text-amber-600 bg-amber-500/10 border-amber-500/20';
    return 'text-indigo-600 bg-indigo-500/10 border-indigo-500/20';
};
</script>

<template>
    <Head :title="isAdminOrTeacher ? 'Schedule Management' : 'My Daily Routine'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-blue-600/10 rounded-lg">
                    <Calendar class="w-4 h-4 text-blue-600" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-gray-500">
                    {{ isAdminOrTeacher ? 'Registry Control' : 'My Routine' }}
                </span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Dramatic Header Section -->
            <div class="relative overflow-hidden glass-card rounded-[2.5rem] border-white/20 p-8 md:p-12">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-600/10 blur-[80px] rounded-full animate-float"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-cyan-600/10 blur-[80px] rounded-full animate-float" style="animation-delay: 2s"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-2 tracking-tighter">
                            {{ isAdminOrTeacher ? 'Master Timetable' : 'Daily Routine' }}
                        </h1>
                        <p class="text-lg text-gray-500 dark:text-gray-400 font-medium">
                            {{ isAdminOrTeacher 
                                ? 'Coordinate academic sessions, room allocations, and faculty schedules.' 
                                : 'Track your lecture times and academic commitments across the week.' }}
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        <Button variant="outline" class="h-14 glass border-white/10 rounded-2xl px-6 font-bold gap-2">
                            <Download class="w-5 h-5" />
                            Export PDF
                        </Button>
                        <Button v-if="isAdminOrTeacher" class="bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl h-14 px-8 shadow-xl shadow-blue-500/20">
                            <Plus class="w-5 h-5 mr-2" />
                            New Session
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-600">
                            <Clock class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter">32h</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Total Weekly Hours</p>
                </div>
                
                <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                            <Coffee class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-emerald-600">05</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Break Segments</p>
                </div>

                <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600">
                            <MapPin class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-amber-600">12</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Assigned Rooms</p>
                </div>

                <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-600">
                            <Users class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-indigo-600">08</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Faculty Members</p>
                </div>
            </div>

            <!-- Weekly Schedule Grid -->
            <div class="glass-card rounded-[3rem] border-white/10 overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-slate-900/50">
                                <th class="p-6 text-left border-b border-white/10 border-r border-white/5 w-32">
                                    <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Time / Day</span>
                                </th>
                                <th v-for="day in days" :key="day" class="p-6 text-center border-b border-white/10">
                                    <span class="text-sm font-black text-gray-900 dark:text-white">{{ day }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="time in timeSlots" :key="time" class="border-b border-white/5">
                                <td class="p-6 text-left border-r border-white/5 bg-gray-50/20 dark:bg-slate-900/20">
                                    <span class="text-xs font-black text-gray-500 italic">{{ time }}</span>
                                </td>
                                
                                <td v-for="day in days" :key="day" class="p-2 min-w-[180px]">
                                    <!-- Dynamic Schedule Block Placeholder -->
                                    <div v-if="day === 'Monday' && time === '08:00 AM'" class="glass border-blue-500/20 rounded-[1.5rem] p-4 group hover:scale-[1.05] transition-all bg-blue-500/5">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600">
                                                <Monitor class="w-5 h-5" />
                                            </div>
                                            <div>
                                                <p class="text-xs font-black text-gray-900 dark:text-white tracking-tight">Advanced Mathematics</p>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Room 201</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <Badge variant="outline" class="text-[9px] h-5 rounded-lg border-white/10 font-bold bg-white/50">8-9 AM</Badge>
                                            <span class="text-[10px] font-black text-blue-600">John. D</span>
                                        </div>
                                    </div>

                                    <div v-else-if="day === 'Tuesday' && time === '10:00 AM'" class="glass border-emerald-500/20 rounded-[1.5rem] p-4 group hover:scale-[1.05] transition-all bg-emerald-500/5">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                                                <Atom class="w-5 h-5" />
                                            </div>
                                            <div>
                                                <p class="text-xs font-black text-gray-900 dark:text-white tracking-tight">Quantum Physics</p>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Lab 101</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <Badge variant="outline" class="text-[9px] h-5 rounded-lg border-white/10 font-bold bg-white/50">10-11 AM</Badge>
                                            <span class="text-[10px] font-black text-emerald-600">Sarah. K</span>
                                        </div>
                                    </div>

                                    <div v-else-if="day === 'Wednesday' && time === '01:00 PM'" class="glass border-amber-500/20 rounded-[1.5rem] p-4 group hover:scale-[1.05] transition-all bg-amber-500/5">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600">
                                                <Palette class="w-5 h-5" />
                                            </div>
                                            <div>
                                                <p class="text-xs font-black text-gray-900 dark:text-white tracking-tight">Creative Arts</p>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Studio 3</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <Badge variant="outline" class="text-[9px] h-5 rounded-lg border-white/10 font-bold bg-white/50">1-2 PM</Badge>
                                            <span class="text-[10px] font-black text-amber-600">Mike. L</span>
                                        </div>
                                    </div>

                                    <!-- Empty State Cell -->
                                    <div v-else class="h-24 rounded-[1.5rem] border border-dashed border-white/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <Plus class="w-6 h-6 text-gray-300" />
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Legend -->
            <div class="flex flex-wrap items-center justify-center gap-6 py-6 border-t border-white/10">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                    <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Mathematics</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                    <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Science</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                    <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Language</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                    <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Arts</span>
                </div>
            </div>
        </div>
    </Sidebar>
</template>

<style scoped>
.animate-float {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(2deg); }
    100% { transform: translateY(0px) rotate(0deg); }
}

.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out forwards;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Hide scrollbar but keep functionality */
.overflow-x-auto::-webkit-scrollbar {
    display: none;
}
.overflow-x-auto {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
