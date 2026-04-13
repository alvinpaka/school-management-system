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
    Save,
    Search,
    Filter,
    Download,
    User,
    Award,
    CheckCircle
} from 'lucide-vue-next';

const props = defineProps({
    exam: Object,
    classes: Array,
    studentsData: Array,
    filters: Object
});

const form = useForm({
    academic_class_id: props.filters.academic_class_id || '',
    section_id: props.filters.section_id || '',
    grades: []
});

const sections = ref([]);

watch(() => form.academic_class_id, (newVal) => {
    const cls = props.classes.find(c => c.id == newVal);
    sections.value = cls ? cls.sections : [];
    if (!sections.value.find(s => s.id == form.section_id)) {
        form.section_id = '';
    }
}, { immediate: true });

watch(() => props.studentsData, (newData) => {
    form.grades = newData.map(s => ({
        student_id: s.student_id,
        name: s.name,
        marks_obtained: s.marks_obtained,
        remarks: s.remarks || ''
    }));
}, { immediate: true });

const fetchStudents = () => {
    router.get(route('exams.grades.enter', props.exam.id), {
        academic_class_id: form.academic_class_id,
        section_id: form.section_id
    }, { preserveState: true });
};

const submit = () => {
    form.post(route('exams.grades.store', props.exam.id), {
        onSuccess: () => alert('Grades saved successfully')
    });
};
</script>

<template>
    <Head title="Enter Grades | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <span class="font-semibold text-warm-text dark:text-dark-text">Enter Grades: {{ exam.name }}</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 animate-fade-in-up">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-warm-text dark:text-dark-text tracking-tighter mb-2">{{ exam.name }}</h1>
                    <p class="text-warm-muted dark:text-dark-muted font-medium">{{ exam.subject.name }} - {{ exam.max_marks }} marks</p>
                </div>
                <div class="flex items-center space-x-3">
                    <Button variant="outline" size="sm" class="border-terracotta/20 text-warm-muted dark:text-dark-muted hover:text-terracotta">
                        <Download class="w-4 h-4 mr-2" />
                        Export Grades
                    </Button>
                    <Button @click="submit" :disabled="form.processing" class="accent-terracotta text-white">
                        <Save class="w-4 h-4 mr-2" />
                        Save Grades
                    </Button>
                </div>
            </div>

            <!-- Filters Card -->
            <div class="card-warm">
                <div class="p-6 border-b border-terracotta/20">
                    <h3 class="text-lg font-black text-warm-text dark:text-dark-text">Class Selection</h3>
                    <p class="text-sm text-warm-muted dark:text-dark-muted mt-1">Select class and section to enter grades</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <Label for="class" class="text-warm-text dark:text-dark-text">Class</Label>
                            <select 
                                id="class" 
                                v-model="form.academic_class_id" 
                                @change="fetchStudents"
                                class="mt-1 block w-full border border-terracotta/20 rounded-lg px-3 py-2 bg-white dark:bg-dark-bg text-warm-text dark:text-dark-text focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta transition-colors"
                            >
                                <option value="">Select Class</option>
                                <option v-for="cls in classes" :key="cls.id" :value="cls.id">{{ cls.name }}</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <Label for="section" class="text-warm-text dark:text-dark-text">Section</Label>
                            <select 
                                id="section" 
                                v-model="form.section_id" 
                                @change="fetchStudents"
                                class="mt-1 block w-full border border-terracotta/20 rounded-lg px-3 py-2 bg-white dark:bg-dark-bg text-warm-text dark:text-dark-text focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta transition-colors"
                            >
                                <option value="">Select Section</option>
                                <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <Badge class="bg-terracotta/10 text-terracotta border-0">
                                <Award class="w-3 h-3 mr-1" />
                                {{ exam.max_marks }} Max Marks
                            </Badge>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grades Entry -->
            <div v-if="form.grades.length > 0" class="card-warm">
                <div class="p-6 border-b border-terracotta/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-black text-warm-text dark:text-dark-text">Student Grades</h3>
                            <p class="text-sm text-warm-muted dark:text-dark-muted mt-1">{{ form.grades.length }} students</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <Badge class="bg-forest/10 text-forest border-0">
                                <CheckCircle class="w-3 h-3 mr-1" />
                                {{ form.grades.filter(g => g.marks_obtained >= exam.passing_marks).length }} Passed
                            </Badge>
                            <Badge class="bg-destructive/10 text-destructive border-0">
                                {{ form.grades.filter(g => g.marks_obtained < exam.passing_marks).length }} Failed
                            </Badge>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div v-for="student in form.grades" :key="student.student_id" class="flex flex-col md:flex-row md:items-center justify-between p-4 border border-terracotta/20 rounded-lg hover:bg-terracotta/5 transition-colors">
                            <div class="flex items-center space-x-3 mb-3 md:mb-0">
                                <div class="w-10 h-10 bg-terracotta/10 rounded-xl flex items-center justify-center">
                                    <User class="w-5 h-5 text-terracotta" />
                                </div>
                                <div>
                                    <div class="font-medium text-warm-text dark:text-dark-text">{{ student.name }}</div>
                                    <div class="text-sm text-warm-muted dark:text-dark-muted">ID: {{ student.student_id }}</div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-4">
                                <div class="flex items-center space-x-2">
                                    <Input
                                        type="number"
                                        v-model="student.marks_obtained"
                                        :min="0"
                                        :max="exam.max_marks"
                                        class="w-20 border-terracotta/20 focus:ring-terracotta/30"
                                        placeholder="Marks"
                                    />
                                    <span class="text-sm text-warm-muted dark:text-dark-muted">/ {{ exam.max_marks }}</span>
                                </div>
                                <Input
                                    v-model="student.remarks"
                                    placeholder="Remarks"
                                    class="w-32 border-terracotta/20 focus:ring-terracotta/30"
                                />
                                <Badge :class="student.marks_obtained >= exam.passing_marks ? 'bg-forest/10 text-forest border-0' : 'bg-destructive/10 text-destructive border-0'">
                                    {{ student.marks_obtained >= exam.passing_marks ? 'Pass' : 'Fail' }}
                                </Badge>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="card-warm">
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-terracotta/5 rounded-3xl flex items-center justify-center mx-auto mb-4">
                        <Award class="w-10 h-10 text-terracotta/30" />
                    </div>
                    <h3 class="text-xl font-black text-warm-text dark:text-dark-text mb-2 tracking-tighter">No Students Found</h3>
                    <p class="text-warm-muted dark:text-dark-muted">Select a class and section to enter grades</p>
                </div>
            </div>
        </div>
    </Sidebar>
</template>