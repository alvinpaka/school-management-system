<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { ref, watch } from 'vue';
import { 
    Plus,
    Search,
    Filter,
    Download,
    Calendar,
    CheckCircle,
    XCircle,
    Clock
} from 'lucide-vue-next';

const props = defineProps({
    classes: Array,
    attendanceData: Array,
    filters: Object
});

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
        status: a.status,
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
        onSuccess: () => alert('Attendance saved successfully')
    });
};
</script>

<template>
    <Head title="Attendance Management" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <CheckCircle class="w-5 h-5" />
                <span>Attendance</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Daily Attendance</h2>
                        <p class="text-gray-600 dark:text-gray-400">Track and manage student attendance</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <Button variant="outline" size="sm">
                            <Download class="w-4 h-4 mr-2" />
                            Export Report
                        </Button>
                        <Button>
                            <Plus class="w-4 h-4 mr-2" />
                            Mark Attendance
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Filters Card -->
            <Card class="mb-6">
                <CardHeader>
                    <CardTitle>Attendance Filters</CardTitle>
                    <CardDescription>Select class and date to mark attendance</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <Label for="class" value="Class" />
                            <select 
                                id="class" 
                                v-model="form.academic_class_id" 
                                @change="fetchAttendance"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-0 rounded-lg"
                            >
                                <option value="">Select Class</option>
                                <option v-for="cls in classes" :key="cls.id" :value="cls.id">{{ cls.name }}</option>
                            </select>
                        </div>
                        <div>
                            <Label for="section" value="Section" />
                            <select 
                                id="section" 
                                v-model="form.section_id" 
                                @change="fetchAttendance"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-0 rounded-lg"
                            >
                                <option value="">Select Section</option>
                                <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
                            </select>
                        </div>
                        <div>
                            <Label for="date" value="Date" />
                            <Input
                                id="date"
                                v-model="form.date"
                                type="date"
                                @change="fetchAttendance"
                                class="mt-1 block w-full"
                            />
                        </div>
                        <div class="flex items-end">
                            <Button @click="submit" :disabled="form.processing">
                                <CheckCircle class="w-4 h-4 mr-2" />
                                Save Attendance
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Attendance List -->
            <Card v-if="form.attendance.length > 0">
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>Student List</CardTitle>
                            <CardDescription>{{ form.attendance.length }} students</CardDescription>
                        </div>
                        <div class="flex items-center space-x-2">
                            <Badge class="bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <CheckCircle class="w-3 h-3 mr-1" />
                                Present: {{ form.attendance.filter(a => a.status === 'present').length }}
                            </Badge>
                            <Badge class="bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                <XCircle class="w-3 h-3 mr-1" />
                                Absent: {{ form.attendance.filter(a => a.status === 'absent').length }}
                            </Badge>
                            <Badge class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                <Clock class="w-3 h-3 mr-1" />
                                Late: {{ form.attendance.filter(a => a.status === 'late').length }}
                            </Badge>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="space-y-3">
                        <div v-for="student in form.attendance" :key="student.student_id" class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium">{{ student.name.charAt(0).toUpperCase() }}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ student.name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">ID: {{ student.student_id }}</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <select 
                                    v-model="student.status" 
                                    class="border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-lg px-3 py-2 text-sm"
                                >
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                    <option value="late">Late</option>
                                </select>
                                <Input
                                    v-model="student.remarks"
                                    placeholder="Remarks"
                                    class="w-32"
                                />
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Empty State -->
            <Card v-else>
                <CardContent class="text-center py-12">
                    <Calendar class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Attendance Data</h3>
                    <p class="text-gray-500 dark:text-gray-400">Select a class, section, and date to mark attendance</p>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
                
