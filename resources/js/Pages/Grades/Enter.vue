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
    <Head title="Enter Grades" />

    <Sidebar>
        <template #header-title>
            Enter Grades: {{ exam.name }}
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ exam.name }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ exam.subject.name }} - {{ exam.max_marks }} marks</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <Button variant="outline" size="sm">
                            <Download class="w-4 h-4 mr-2" />
                            Export Grades
                        </Button>
                        <Button @click="submit" :disabled="form.processing">
                            <Save class="w-4 h-4 mr-2" />
                            Save Grades
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Filters Card -->
            <Card class="mb-6">
                <CardHeader>
                    <CardTitle>Class Selection</CardTitle>
                    <CardDescription>Select class and section to enter grades</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <Label for="class" value="Class" />
                            <select 
                                id="class" 
                                v-model="form.academic_class_id" 
                                @change="fetchStudents"
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
                                @change="fetchStudents"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-0 rounded-lg"
                            >
                                <option value="">Select Section</option>
                                <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <Badge class="bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                <Award class="w-3 h-3 mr-1" />
                                {{ exam.max_marks }} Max Marks
                            </Badge>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Grades Entry -->
            <Card v-if="form.grades.length > 0">
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>Student Grades</CardTitle>
                            <CardDescription>{{ form.grades.length }} students</CardDescription>
                        </div>
                        <div class="flex items-center space-x-2">
                            <Badge class="bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <CheckCircle class="w-3 h-3 mr-1" />
                                {{ form.grades.filter(g => g.marks_obtained >= exam.passing_marks).length }} Passed
                            </Badge>
                            <Badge class="bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                {{ form.grades.filter(g => g.marks_obtained < exam.passing_marks).length }} Failed
                            </Badge>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="space-y-3">
                        <div v-for="student in form.grades" :key="student.student_id" class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                                    <User class="w-5 h-5 text-white" />
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ student.name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">ID: {{ student.student_id }}</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <Input
                                        type="number"
                                        v-model="student.marks_obtained"
                                        :min="0"
                                        :max="exam.max_marks"
                                        class="w-20"
                                        placeholder="Marks"
                                    />
                                    <span class="text-sm text-gray-500 dark:text-gray-400">/ {{ exam.max_marks }}</span>
                                </div>
                                <Input
                                    v-model="student.remarks"
                                    placeholder="Remarks"
                                    class="w-32"
                                />
                                <Badge :class="student.marks_obtained >= exam.passing_marks ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'">
                                    {{ student.marks_obtained >= exam.passing_marks ? 'Pass' : 'Fail' }}
                                </Badge>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Empty State -->
            <Card v-else>
                <CardContent class="text-center py-12">
                    <Award class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Students Found</h3>
                    <p class="text-gray-500 dark:text-gray-400">Select a class and section to enter grades</p>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
