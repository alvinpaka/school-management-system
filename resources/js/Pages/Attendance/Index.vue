<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, watch } from 'vue';

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
    <Head title="Attendance" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Daily Attendance
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white border border-gray-200 p-6 mb-6 dark:bg-gray-800 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
                            <InputLabel for="date" value="Date" />
                            <TextInput id="date" type="date" class="mt-1 block w-full" v-model="form.date" />
                        </div>
                        <div>
                            <PrimaryButton @click="fetchAttendance" :disabled="!form.academic_class_id || !form.section_id">
                                Get List
                            </PrimaryButton>
                        </div>
                    </div>
                </div>

                <div v-if="form.attendance.length > 0" class="bg-white border border-gray-200 overflow-hidden dark:bg-gray-800 dark:border-gray-700">
                    <form @submit.prevent="submit">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                <tr v-for="(item, index) in form.attendance" :key="index">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ item.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" :name="'status_' + index" value="present" v-model="form.attendance[index].status" class="text-green-600 focus:ring-0">
                                                <span class="ml-2">Present</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" :name="'status_' + index" value="absent" v-model="form.attendance[index].status" class="text-red-600 focus:ring-0">
                                                <span class="ml-2">Absent</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" :name="'status_' + index" value="late" v-model="form.attendance[index].status" class="text-yellow-600 focus:ring-0">
                                                <span class="ml-2">Late</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <TextInput type="text" v-model="form.attendance[index].remarks" class="block w-full text-xs" placeholder="Optional remarks" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="p-6 bg-gray-50 dark:bg-gray-900 flex justify-end">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Save Attendance
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
