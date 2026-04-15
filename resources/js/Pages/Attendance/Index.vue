<script setup>
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
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
        case 'present': return 'text-forest bg-forest/10 border-forest/20';
        case 'absent': return 'text-destructive bg-destructive/10 border-destructive/20';
        case 'late': return 'text-amber bg-amber/10 border-amber/20';
        default: return 'text-warm-muted bg-warm-muted/10 border-warm-muted/20';
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
    <Head :title="isAdminOrTeacher ? 'Attendance Management | EduManage Pro' : 'My Attendance | EduManage Pro'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-forest/10 rounded-lg">
                    <CheckCircle class="w-4 h-4 text-forest" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted text-dark-muted">
                    {{ isAdminOrTeacher ? 'Attendance Console' : 'My Attendance' }}
                </span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Dramatic Header Section -->
            <div class="relative overflow-hidden card-warm rounded-[2.5rem] p-8 md:p-12">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-forest/5 blur-[80px] rounded-full animate-float"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-terracotta/5 blur-[80px] rounded-full animate-float" style="animation-delay: 2s"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-black text-warm-text text-dark-text mb-2 tracking-tighter">
                            {{ isAdminOrTeacher ? 'Daily Attendance' : 'Attendance History' }}
                        </h1>
                        <p class="text-lg text-warm-muted text-dark-muted font-medium">
                            {{ isAdminOrTeacher 
                                ? 'Review and mark student presence for administrative records.' 
                                : 'Track your academic consistency and presence registry.' }}
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="bg-white bg-dark-bg border border-terracotta/20 shadow-sm px-6 py-3 rounded-2xl flex flex-col items-end">
                            <p class="text-[10px] font-black uppercase tracking-widest text-terracotta mb-1">Target Date</p>
                            <div class="flex items-center gap-2 font-bold text-warm-text text-dark-text">
                                <Calendar class="w-4 h-4 text-terracotta" />
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
                    <div class="card-warm rounded-[2rem] overflow-hidden">
                        <div class="p-6 border-b border-terracotta/20">
                            <h3 class="text-lg font-black text-warm-text text-dark-text">Configuration</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="space-y-2">
                                <Label class="text-[10px] font-black uppercase text-warm-muted text-dark-muted ml-1">Academic Class</Label>
                                <select 
                                    v-model="form.academic_class_id" 
                                    @change="fetchAttendance"
                                    class="w-full bg-white bg-dark-bg border border-terracotta/20 shadow-sm rounded-2xl h-12 px-4 text-sm font-bold focus:ring-2 focus:ring-terracotta/30 transition-all outline-none text-warm-text text-dark-text"
                                >
                                    <option value="">Select Class</option>
                                    <option v-for="cls in classes" :key="cls.id" :value="cls.id">{{ cls.name }}</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <Label class="text-[10px] font-black uppercase text-warm-muted text-dark-muted ml-1">Section</Label>
                                <select 
                                    v-model="form.section_id" 
                                    @change="fetchAttendance"
                                    class="w-full bg-white bg-dark-bg border border-terracotta/20 shadow-sm rounded-2xl h-12 px-4 text-sm font-bold focus:ring-2 focus:ring-terracotta/30 transition-all outline-none text-warm-text text-dark-text"
                                >
                                    <option value="">Select Section</option>
                                    <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <Label class="text-[10px] font-black uppercase text-warm-muted text-dark-muted ml-1">Report Date</Label>
                                <Input
                                    v-model="form.date"
                                    type="date"
                                    @change="fetchAttendance"
                                    class="h-12 bg-white bg-dark-bg border border-terracotta/20 shadow-sm rounded-2xl font-bold text-warm-text text-dark-text"
                                />
                            </div>

                            <Button 
                                @click="submit" 
                                :disabled="form.processing || !form.academic_class_id"
                                class="w-full h-12 accent-terracotta text-white font-black rounded-2xl shadow-lg shadow-terracotta/30 mt-4"
                            >
                                <ClipboardList class="w-4 h-4 mr-2" />
                                Commit Changes
                            </Button>
                        </div>
                    </div>

                    <!-- Quick Stats Summary -->
                    <div class="card-warm p-6 rounded-[2rem] space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black uppercase text-warm-muted text-dark-muted">Section Health</span>
                            <Badge class="bg-forest/10 text-forest border-0 font-bold">{{ stats.percent }}%</Badge>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-forest/5 border border-forest/20 text-center text-forest">
                                <p class="text-[10px] font-black uppercase opacity-60">Present</p>
                                <p class="text-2xl font-black tracking-tighter">{{ stats.present }}</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-destructive/5 border border-destructive/20 text-center text-destructive">
                                <p class="text-[10px] font-black uppercase opacity-60">Absent</p>
                                <p class="text-2xl font-black tracking-tighter">{{ stats.absent }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Grid -->
                <div class="lg:col-span-3 space-y-6">
                    <div v-if="form.attendance.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <div 
                            v-for="(student, idx) in form.attendance" 
                            :key="student.student_id"
                            class="card-warm rounded-[2.5rem] overflow-hidden group hover:scale-[1.02] transition-all duration-300"
                        >
                            <div class="p-6">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="relative">
                                        <div 
                                            class="absolute -inset-1 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000"
                                            :class="getStatusColor(student.status)"
                                        ></div>
                                        <div class="w-16 h-16 bg-white bg-dark-bg rounded-2xl flex items-center justify-center text-xl font-black border border-terracotta/20 overflow-hidden shrink-0 relative z-10">
                                            <img v-if="student.photo" :src="`/storage/${student.photo}`" class="object-cover w-full h-full" />
                                            <span v-else class="text-terracotta capitalize">{{ student.name.charAt(0) }}</span>
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-black text-warm-text text-dark-text truncate tracking-tight">{{ student.name }}</h3>
                                        <p class="text-xs font-bold text-warm-muted text-dark-muted uppercase tracking-widest">ID: #{{ student.student_id }}</p>
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
                                                ? 'bg-forest text-white shadow-lg shadow-forest/30' 
                                                : 'bg-terracotta/5 text-forest border border-terracotta/20 hover:bg-forest/10'
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
                                                ? 'bg-destructive text-white shadow-lg shadow-destructive/30' 
                                                : 'bg-terracotta/5 text-destructive border border-terracotta/20 hover:bg-destructive/10'
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
                                                ? 'bg-amber text-white shadow-lg shadow-amber/30' 
                                                : 'bg-terracotta/5 text-amber border border-terracotta/20 hover:bg-amber/10'
                                        ]"
                                    >
                                        Late
                                    </button>
                                </div>

                                <Input
                                    v-model="student.remarks"
                                    placeholder="Add optional notes..."
                                    class="h-10 bg-white bg-dark-bg border border-terracotta/20 rounded-xl text-sm text-warm-text text-dark-text"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="card-warm rounded-[3rem] p-20 text-center">
                        <div class="w-24 h-24 bg-terracotta/5 rounded-3xl flex items-center justify-center mx-auto mb-6">
                            <Users class="w-12 h-12 text-terracotta/30" />
                        </div>
                        <h3 class="text-2xl font-black text-warm-text text-dark-text tracking-tighter mb-2">No Students Selected</h3>
                        <p class="text-warm-muted text-dark-muted font-medium mb-8">Choose an academic class and section to begin marking attendance.</p>
                        <Button variant="ghost" class="font-bold gap-2 text-terracotta hover:bg-terracotta/10">
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
                    <div class="card-warm p-6 rounded-[2rem] group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-forest/10 flex items-center justify-center text-forest">
                                <CheckCircle class="w-6 h-6" />
                            </div>
                            <span class="text-2xl font-black tracking-tighter text-forest">98%</span>
                        </div>
                        <p class="text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Attendance Rate</p>
                    </div>
                    
                    <div class="card-warm p-6 rounded-[2rem] group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                                <ClipboardList class="w-6 h-6" />
                            </div>
                            <span class="text-2xl font-black tracking-tighter text-warm-text text-dark-text">182</span>
                        </div>
                        <p class="text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Days Present</p>
                    </div>

                    <div class="card-warm p-6 rounded-[2rem] group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-destructive/10 flex items-center justify-center text-destructive">
                                <XCircle class="w-6 h-6" />
                            </div>
                            <span class="text-2xl font-black tracking-tighter text-destructive">03</span>
                        </div>
                        <p class="text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Days Absent</p>
                    </div>

                    <div class="card-warm p-6 rounded-[2rem] group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber/10 flex items-center justify-center text-amber">
                                <Clock class="w-6 h-6" />
                            </div>
                            <span class="text-2xl font-black tracking-tighter text-amber">02</span>
                        </div>
                        <p class="text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Late Arrivals</p>
                    </div>
                </div>

                <!-- History Table & Timeline -->
                <div class="card-warm rounded-[2.5rem] overflow-hidden">
                    <div class="p-8 border-b border-terracotta/20 bg-terracotta/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-black tracking-tighter text-warm-text text-dark-text">Activity History</h3>
                                <p class="font-medium text-warm-muted text-dark-muted">Chronological record of your presence</p>
                            </div>
                            <Button variant="outline" class="bg-white bg-dark-bg border border-terracotta/20 rounded-xl px-6 font-bold text-warm-muted text-dark-muted hover:text-terracotta">
                                <Download class="w-4 h-4 mr-2" />
                                Export CSV
                            </Button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-terracotta/5 text-left border-b border-terracotta/20">
                                    <th class="py-5 px-8 text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Date</th>
                                    <th class="py-5 px-8 text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Session</th>
                                    <th class="py-5 px-8 text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest text-center">Status</th>
                                    <th class="py-5 px-8 text-[10px] font-black uppercase text-warm-muted text-dark-muted tracking-widest">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr 
                                    v-for="record in props.attendanceData" 
                                    :key="record.id"
                                    class="border-b border-terracotta/20 hover:bg-terracotta/5 transition-colors"
                                >
                                    <td class="py-5 px-8">
                                        <div class="font-bold text-warm-text text-dark-text">{{ new Date(record.date).toLocaleDateString() }}</div>
                                        <div class="text-[10px] font-black uppercase text-warm-muted text-dark-muted">{{ formatLongDate(record.date).split(',')[0] }}</div>
                                    </td>
                                    <td class="py-5 px-8 font-medium text-warm-muted text-dark-muted">Standard Academic Session</td>
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
                                    <td class="py-5 px-8 text-sm font-medium text-warm-muted text-dark-muted italic">
                                        {{ record.remarks || 'No remarks provided' }}
                                    </td>
                                </tr>
                                <tr v-if="props.attendanceData.length === 0">
                                    <td colspan="4" class="py-20 text-center text-warm-muted text-dark-muted font-bold italic">
                                        No attendance records found for the current period.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </Sidebar>
</template>