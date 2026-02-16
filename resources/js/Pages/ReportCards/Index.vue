<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
    Users,
    FileText,
    Download,
    Share,
    Eye,
    Home,
    Search,
    Filter
} from 'lucide-vue-next';

const props = defineProps({
    students: Object,
    filters: Object
});

const searchQuery = ref(props.filters?.search || '');
const selectedClass = ref(props.filters?.class || 'S1');

const classes = [
    { id: 'S1', name: 'S1', label: 'Senior 1' },
    { id: 'S2', name: 'S2', label: 'Senior 2' },
    { id: 'S3', name: 'S3', label: 'Senior 3' },
    { id: 'S4', name: 'S4', label: 'Senior 4' },
    { id: 'S5', name: 'S5', label: 'Senior 5' },
    { id: 'S6', name: 'S6', label: 'Senior 6' }
];

const generateReportCard = (studentId) => {
    window.open(route('report-cards.generate', studentId), '_blank');
};

const shareViaWhatsApp = (studentId) => {
    const phoneNumber = prompt('Enter parent phone number (with country code):');
    if (phoneNumber) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = route('report-cards.share-whatsapp', studentId);
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        // Try multiple ways to get CSRF token
        const tokenElement = document.querySelector('meta[name="csrf-token"]') || 
                          document.querySelector('meta[name="x-csrf-token"]') ||
                          document.querySelector('input[name="_token"]');
        csrfToken.value = tokenElement ? tokenElement.getAttribute('content') || tokenElement.value : '';
        form.appendChild(csrfToken);
        
        const phoneInput = document.createElement('input');
        phoneInput.type = 'hidden';
        phoneInput.name = 'phone_number';
        phoneInput.value = phoneNumber;
        form.appendChild(phoneInput);
        
        document.body.appendChild(form);
        form.submit();
    }
};

const selectClass = (classId) => {
    selectedClass.value = classId;
    const params = { class: classId };
    if (searchQuery.value) {
        params.search = searchQuery.value;
    }
    router.get(route('report-cards.index'), params, { preserveState: true });
};

// Watch for search query changes
watch(searchQuery, (newValue) => {
    const params = { class: selectedClass.value };
    if (newValue) {
        params.search = newValue;
    }
    router.get(route('report-cards.index'), params, { preserveState: true });
}, { debounce: 300 });
</script>

<template>
    <Head title="Report Cards" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <FileText class="w-5 h-5" />
                <span>Report Cards</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Search and Filter Section -->
            <Card class="mb-6">
                <CardContent class="p-6">
                    <div class="flex flex-col lg:flex-row gap-4">
                        <!-- Search Bar -->
                        <div class="flex-1">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search students by name or admission number..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                />
                            </div>
                        </div>
                        
                        <!-- Class Tabs -->
                        <div class="flex flex-wrap gap-2">
                            <Button
                                v-for="classItem in classes"
                                :key="classItem.id"
                                @click="selectClass(classItem.id)"
                                :variant="selectedClass === classItem.id ? 'default' : 'outline'"
                                size="sm"
                                class="flex items-center space-x-2"
                            >
                                <Users class="w-4 h-4" />
                                <span>{{ classItem.label }}</span>
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Students Grid -->
            <div v-if="props.students && props.students.data && props.students.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div
                    v-for="student in props.students.data"
                    :key="student.id"
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 p-6"
                >
                    <!-- Student Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600">
                                <img 
                                    v-if="student.user?.photo" 
                                    :src="`/storage/${student.user.photo}`" 
                                    :alt="student.user?.name"
                                    class="w-full h-full object-cover"
                                />
                                <div v-else class="flex items-center justify-center h-full">
                                    <span class="text-blue-500 text-sm font-bold">{{ student.user?.name?.charAt(0) || 'S' }}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ student.user?.name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ student.admission_number }}</p>
                            </div>
                        </div>
                        
                        <!-- Class Badge -->
                        <Badge class="bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ student.academic_class?.name || 'N/A' }} - {{ student.section?.name || 'N/A' }}
                        </Badge>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex space-x-2">
                        <Button
                            @click="generateReportCard(student.id)"
                            variant="outline"
                            size="sm"
                            class="flex items-center space-x-1"
                        >
                            <FileText class="w-3 h-3" />
                            Generate
                        </Button>
                        
                        <Button
                            @click="shareViaWhatsApp(student.id)"
                            variant="outline"
                            size="sm"
                            class="flex items-center space-x-1"
                        >
                            <Share class="w-3 h-3" />
                            Share
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
                <div class="text-gray-500 dark:text-gray-400">
                    <FileText class="w-12 h-12 mx-auto mb-4 text-gray-300" />
                    <h3 class="text-lg font-medium mb-2">No students found</h3>
                    <p class="text-sm">
                        {{ searchQuery ? `No students found matching "${searchQuery}"` : 'No students found in this class' }}
                    </p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="props.students && props.students.links && props.students.links.length > 3" class="mt-8 flex justify-center">
                <Link
                    v-for="(link, index) in props.students.links"
                    :key="index"
                    :href="link.url"
                    v-html="link.label"
                    class="px-3 py-2 mx-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                    :class="{
                        'bg-blue-600 text-white border-blue-600': link.active,
                        'text-gray-700 dark:text-gray-300': !link.active
                    }"
                />
            </div>
        </div>
    </Sidebar>
</template>
