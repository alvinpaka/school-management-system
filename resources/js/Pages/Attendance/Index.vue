<script setup>
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { ref, watch, computed } from 'vue';
import { 
    Plus,
    Search,
    Filter,
    Download,
    Calendar,
    CheckCircle,
    XCircle,
    Clock,
    UserCheck,
    ChevronLeft,
    ChevronRight,
    Users,
    ClipboardList,
    AlertCircle,
    History,
    Activity,
    Info
} from 'lucide-vue-next';

const props = defineProps({
    classes: Array,
    attendanceData: Array,
    filters: Object
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRoles = computed(() => user.value?.roles || []);
const isAdminOrTeacher = computed(() => userRoles.value.some(r => ['admin', 'teacher'].includes(r)));

const form = useForm({
    academic_class_id: props.filters.academic_class_id || '',
    section_id: props.filters.section_id || '',
    date: props.filters.date || new Date().toISOString().substr(0, 10),
    attendance: []
});

const sections = ref([]);

watch(() => form.academic_class_id, (newVal) => {
    const cls = props.classes.find(c => c.id == newVal);
    sections.value = cls ? cls.sections : [];
    if (!sections.value.find(s => s.id == form.section_id)) {
        form.section_id = '';
    }
}, { immediate: true });

// Sync attendanceData to form
watch(() => props.attendanceData, (newData) => {
    form.attendance = newData.map(a => ({
        student_id: a.student_id || a.id,
        name: a.student?.user?.name || a.user?.name,
        photo: a.student?.user?.photo || a.user?.photo,
        status: a.status || 'present',
        remarks: a.remarks || ''
    }));
}, { immediate: true });

const fetchAttendance = () => {
    router.get(route('attendance.index'), {
        academic_class_id: form.academic_class_id,
        section_id: form.section_id,
        date: form.date
    }, { preserveState: true });
};

const submit = () => {
    form.post(route('attendance.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // Success logic if needed
        }
    });
};

const setStatus = (studentIndex, status) => {
    form.attendance[studentIndex].status = status;
};

const getStatusColor = (status) => {
    switch (status) {
        case 'present': return 'text-emerald-600 bg-emerald-500/10 border-emerald-500/20';
        case 'absent': return 'text-rose-600 bg-rose-500/10 border-rose-500/20';
        case 'late': return 'text-amber-600 bg-amber-500/10 border-amber-500/20';
        default: return 'text-slate-600 bg-slate-500/10 border-slate-500/20';
    }
};

const formatLongDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
};

const stats = computed(() => {
    const total = form.attendance.length;
    if (total === 0) return { present: 0, absent: 0, late: 0, percent: 0 };
    
    const present = form.attendance.filter(a => a.status === 'present').length;
    const absent = form.attendance.filter(a => a.status === 'absent').length;
    const late = form.attendance.filter(a => a.status === 'late').length;
    
    return {
        present,
        absent,
        late,
        percent: Math.round((present / total) * 100)
    };
});
</script>

<template>
    <Head :title="isAdminOrTeacher ? 'Attendance Management' : 'My Attendance'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-emerald-600/10 rounded-lg">
                    <CheckCircle class="w-4 h-4 text-emerald-600" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-gray-500">
                    {{ isAdminOrTeacher ? 'Attendance Console' : 'My Attendance' }}
                </span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Dramatic Header Section -->
            <div class="relative overflow-hidden glass-card rounded-[2.5rem] border-white/20 p-8 md:p-12">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-600/10 blur-[80px] rounded-full animate-float"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-teal-600/10 blur-[80px] rounded-full animate-float" style="animation-delay: 2s"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-2 tracking-tighter">
                            {{ isAdminOrTeacher ? 'Daily Attendance' : 'Attendance History' }}
                        </h1>
                        <p class="text-lg text-gray-500 dark:text-gray-400 font-medium">
                            {{ isAdminOrTeacher 
                                ? 'Review and mark student presence for administrative records.' 
                                : 'Track your academic consistency and presence registry.' }}
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="glass px-6 py-3 rounded-2xl border-white/10 flex flex-col items-end">
                            <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-1">Target Date</p>
                            <div class="flex items-center gap-2 font-bold text-gray-900 dark:text-white">
                                <Calendar class="w-4 h-4 text-emerald-500" />
                                {{ formatLongDate(form.date) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Management Tools (Admin/Teacher Only) -->
            <div v-if="isAdminOrTeacher" class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Control Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <Card class="glass shadow-xl border-white/10 rounded-[2rem] overflow-hidden">
                        <CardHeader class="pb-2">
                            <CardTitle class="text-lg font-black tracking-tight">Configuration</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="space-y-2">
                                <Label class="text-[10px] font-black uppercase text-gray-400 ml-1">Academic Class</Label>
                                <select 
                                    v-model="form.academic_class_id" 
                                    @change="fetchAttendance"
                                    class="w-full glass bg-white/50 border-white/10 rounded-2xl h-12 px-4 text-sm font-bold focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none"
                                >
                                    <option value="">Select Class</option>
                                    <option v-for="cls in classes" :key="cls.id" :value="cls.id">{{ cls.name }}</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <Label class="text-[10px] font-black uppercase text-gray-400 ml-1">Section</Label>
                                <select 
                                    v-model="form.section_id" 
                                    @change="fetchAttendance"
                                    class="w-full glass bg-white/50 border-white/10 rounded-2xl h-12 px-4 text-sm font-bold focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none"
                                >
                                    <option value="">Select Section</option>
                                    <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <Label class="text-[10px] font-black uppercase text-gray-400 ml-1">Report Date</Label>
                                <Input
                                    v-model="form.date"
                                    type="date"
                                    @change="fetchAttendance"
                                    class="h-12 glass bg-white/50 border-white/10 rounded-2xl font-bold"
                                />
                            </div>

                            <Button 
                                @click="submit" 
                                :disabled="form.processing || !form.academic_class_id"
                                class="w-full h-12 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-lg shadow-emerald-500/20 mt-4"
                            >
                                <ClipboardList class="w-4 h-4 mr-2" />
                                Commit Changes
                            </Button>
                        </CardContent>
                    </Card>

                    <!-- Quick Stats Summary -->
                    <div class="glass-card p-6 rounded-[2rem] border-white/10 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black uppercase text-gray-400">Section Health</span>
                            <Badge class="bg-emerald-500/10 text-emerald-600 border-0 font-bold">{{ stats.percent }}%</Badge>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-emerald-500/5 border border-emerald-500/10 text-center text-emerald-600">
                                <p class="text-[10px] font-black uppercase opacity-60">Present</p>
                                <p class="text-2xl font-black tracking-tighter">{{ stats.present }}</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-rose-500/5 border border-rose-500/10 text-center text-rose-600">
                                <p class="text-[10px] font-black uppercase opacity-60">Absent</p>
                                <p class="text-2xl font-black tracking-tighter">{{ stats.absent }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Grid -->
                <div class="lg:col-span-3 space-y-6">
                    <div v-if="form.attendance.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <Card 
                            v-for="(student, idx) in form.attendance" 
                            :key="student.student_id"
                            class="glass border-white/10 rounded-[2.5rem] overflow-hidden group hover:scale-[1.02] transition-all duration-300"
                        >
                            <CardContent class="p-6">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="relative">
                                        <div 
                                            class="absolute -inset-1 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000"
                                            :class="getStatusColor(student.status)"
                                        ></div>
                                        <div class="w-16 h-16 bg-white/50 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-xl font-black border border-white/10 overflow-hidden shrink-0 relative z-10">
                                            <img v-if="student.photo" :src="`/storage/${student.photo}`" class="object-cover w-full h-full" />
                                            <span v-else class="text-slate-400 capitalize">{{ student.name.charAt(0) }}</span>
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-black text-gray-900 dark:text-white truncate tracking-tight">{{ student.name }}</h3>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">ID: #{{ student.student_id }}</p>
                                    </div>
                                </div>

                                <!-- Status Controls -->
                                <div class="grid grid-cols-3 gap-2 mb-4">
                                    <button 
                                        type="button"
                                        @click="setStatus(idx, 'present')"
                                        :class="[
                                            'h-10 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all',
                                            student.status === 'present' 
                                                ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/20' 
                                                : 'glass text-emerald-600 border-white/10 hover:bg-emerald-50'
                                        ]"
                                    >
                                        Present
                                    </button>
                                    <button 
                                        type="button"
                                        @click="setStatus(idx, 'absent')"
                                        :class="[
                                            'h-10 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all',
                                            student.status === 'absent' 
                                                ? 'bg-rose-600 text-white shadow-lg shadow-rose-500/20' 
                                                : 'glass text-rose-600 border-white/10 hover:bg-rose-50'
                                        ]"
                                    >
                                        Absent
                                    </button>
                                    <button 
                                        type="button"
                                        @click="setStatus(idx, 'late')"
                                        :class="[
                                            'h-10 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all',
                                            student.status === 'late' 
                                                ? 'bg-amber-600 text-white shadow-lg shadow-amber-500/20' 
                                                : 'glass text-amber-600 border-white/10 hover:bg-amber-50'
                                        ]"
                                    >
                                        Late
                                    </button>
                                </div>

                                <Input
                                    v-model="student.remarks"
                                    placeholder="Add optional notes..."
                                    class="h-10 glass bg-white/50 border-white/10 rounded-xl text-xs"
                                />
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="glass rounded-[3rem] border-white/10 p-20 text-center">
                        <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-6">
                            <Users class="w-12 h-12 text-slate-400" />
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter mb-2">No Students Selected</h3>
                        <p class="text-gray-500 font-medium mb-8">Choose an academic class and section to begin marking attendance.</p>
                        <Button variant="ghost" class="font-bold gap-2 text-emerald-600">
                            <Info class="w-4 h-4" />
                            Help Center
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Student/Parent View (History & Insights) -->
            <div v-else class="space-y-8">
                <!-- Insights Stat Bar -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                                <CheckCircle class="w-6 h-6" />
                            </div>
                            <span class="text-2xl font-black tracking-tighter text-emerald-600">98%</span>
                        </div>
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Attendance Rate</p>
                    </div>
                    
                    <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-600">
                                <ClipboardList class="w-6 h-6" />
                            </div>
                            <span class="text-2xl font-black tracking-tighter">182</span>
                        </div>
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Days Present</p>
                    </div>

                    <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 flex items-center justify-center text-rose-600">
                                <XCircle class="w-6 h-6" />
                            </div>
                            <span class="text-2xl font-black tracking-tighter text-rose-600">03</span>
                        </div>
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Days Absent</p>
                    </div>

                    <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600">
                                <Clock class="w-6 h-6" />
                            </div>
                            <span class="text-2xl font-black tracking-tighter text-amber-600">02</span>
                        </div>
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Late Arrivals</p>
                    </div>
                </div>

                <!-- History Table & Timeline -->
                <Card class="glass shadow-2xl border-white/10 rounded-[2.5rem] overflow-hidden">
                    <CardHeader class="p-8 border-b border-white/5 bg-white/50 dark:bg-white/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <CardTitle class="text-2xl font-black tracking-tighter">Activity History</CardTitle>
                                <CardDescription class="font-medium text-gray-500">Chronological record of your presence</CardDescription>
                            </div>
                            <Button variant="outline" class="glass rounded-xl px-6 border-white/10 font-bold">
                                <Download class="w-4 h-4 mr-2" />
                                Export CSV
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent class="p-0">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50/50 dark:bg-slate-900/50 text-left border-b border-white/10">
                                        <th class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Date</th>
                                        <th class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Session</th>
                                        <th class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest text-center">Status</th>
                                        <th class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr 
                                        v-for="record in props.attendanceData" 
                                        :key="record.id"
                                        class="border-b border-white/5 hover:bg-white/5 dark:hover:bg-slate-800/50 transition-colors"
                                    >
                                        <td class="py-5 px-8">
                                            <div class="font-bold text-gray-900 dark:text-white">{{ new Date(record.date).toLocaleDateString() }}</div>
                                            <div class="text-[10px] font-black uppercase text-gray-400">{{ formatLongDate(record.date).split(',')[0] }}</div>
                                        </td>
                                        <td class="py-5 px-8 font-medium text-gray-500">Standard Academic Session</td>
                                        <td class="py-5 px-8 text-center">
                                            <Badge 
                                                :class="[
                                                    'rounded-full px-4 py-1 h-7 border-0 font-black uppercase text-[9px] tracking-widest',
                                                    getStatusColor(record.status)
                                                ]"
                                            >
                                                {{ record.status }}
                                            </Badge>
                                        </td>
                                        <td class="py-5 px-8 text-sm font-medium text-gray-500 italic">
                                            {{ record.remarks || 'No remarks provided' }}
                                        </td>
                                    </tr>
                                    <tr v-if="props.attendanceData.length === 0">
                                        <td colspan="4" class="py-20 text-center text-gray-400 font-bold italic">
                                            No attendance records found for the current period.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>
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
</style>
                
