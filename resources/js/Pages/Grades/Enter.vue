<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, watch } from 'vue';

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

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Enter Grades: {{ exam.name }} ({{ exam.subject.name }})
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white border border-gray-200 p-6 mb-6 dark:bg-gray-800 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <InputLabel for="class" value="Class" />
                            <select id="class" v-model="form.academic_class_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-0 rounded-none">
                                <option value="">Select Class</option>
                                <option v-for="cls in classes" :key="cls.id" :value="cls.id">{{ cls.name }}</option>
                            </select>
                        </div>
                        <div>
                            <InputLabel for="section" value="Section" />
                            <select id="section" v-model="form.section_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-0 rounded-none">
                                <option value="">Select Section</option>
                                <option v-for="sec in sections" :key="sec.id" :value="sec.id">{{ sec.name }}</option>
                            </select>
                        </div>
                        <div>
                            <PrimaryButton @click="fetchStudents" :disabled="!form.academic_class_id || !form.section_id">
                                Get Student List
                            </PrimaryButton>
                        </div>
                    </div>
                </div>

                <div v-if="form.grades.length > 0" class="bg-white border border-gray-200 overflow-hidden dark:bg-gray-800 dark:border-gray-700">
                    <form @submit.prevent="submit">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marks Obtained (Max: {{ exam.max_marks }})</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                <tr v-for="(item, index) in form.grades" :key="index">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ item.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <TextInput type="number" v-model="form.grades[index].marks_obtained" class="block w-32" :max="exam.max_marks" min="0" required />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <TextInput type="text" v-model="form.grades[index].remarks" class="block w-full text-xs" placeholder="Optional remarks" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="p-6 bg-gray-50 dark:bg-gray-900 flex justify-end">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Save Grades
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
                <div v-else-if="filters.academic_class_id" class="p-6 text-center text-gray-500">
                    No students found for this class and section.
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
