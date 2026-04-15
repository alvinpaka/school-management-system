<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
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
    <Head title="Report Cards | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <FileText class="w-5 h-5 text-terracotta" />
                <span class="font-semibold text-warm-text text-dark-text">Report Cards</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 animate-fade-in-up">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter mb-2">Report Cards</h1>
                    <p class="text-warm-muted text-dark-muted font-medium">Generate and share student report cards</p>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="card-warm">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row gap-4">
                        <!-- Search Bar -->
                        <div class="flex-1">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-warm-muted text-dark-muted" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search students by name or admission number..."
                                    class="w-full pl-10 pr-4 py-2 border border-terracotta/20 rounded-lg focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta bg-white bg-dark-bg text-warm-text text-dark-text"
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
                                :class="selectedClass === classItem.id ? 'accent-terracotta text-white' : 'border-terracotta/20 text-warm-muted text-dark-muted hover:text-terracotta'"
                                class="flex items-center space-x-2"
                            >
                                <Users class="w-4 h-4" />
                                <span>{{ classItem.label }}</span>
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Grid -->
            <div v-if="props.students && props.students.data && props.students.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div
                    v-for="student in props.students.data"
                    :key="student.id"
                    class="card-warm p-6 transition-all duration-300 hover:scale-[1.02]"
                >
                    <!-- Student Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-terracotta/10 border-2 border-terracotta/20">
                                <img 
                                    v-if="student.user?.photo" 
                                    :src="`/storage/${student.user.photo}`" 
                                    :alt="student.user?.name"
                                    class="w-full h-full object-cover"
                                />
                                <div v-else class="flex items-center justify-center h-full">
                                    <span class="text-terracotta text-lg font-black">{{ student.user?.name?.charAt(0) || 'S' }}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-warm-text text-dark-text">{{ student.user?.name }}</h3>
                                <p class="text-sm text-warm-muted text-dark-muted">{{ student.admission_number }}</p>
                            </div>
                        </div>
                        
                        <!-- Class Badge -->
                        <Badge class="bg-terracotta/10 text-terracotta border-0">
                            {{ student.academic_class?.name || 'N/A' }} - {{ student.section?.name || 'N/A' }}
                        </Badge>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex space-x-2 mt-4">
                        <Button
                            @click="generateReportCard(student.id)"
                            variant="outline"
                            size="sm"
                            class="flex-1 border-terracotta/20 text-terracotta hover:bg-terracotta/10"
                        >
                            <FileText class="w-3 h-3 mr-2" />
                            Generate
                        </Button>
                        
                        <Button
                            @click="shareViaWhatsApp(student.id)"
                            variant="outline"
                            size="sm"
                            class="flex-1 border-terracotta/20 text-terracotta hover:bg-terracotta/10"
                        >
                            <Share class="w-3 h-3 mr-2" />
                            Share
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="card-warm text-center py-16">
                <div class="w-20 h-20 bg-terracotta/5 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <FileText class="w-10 h-10 text-terracotta/30" />
                </div>
                <h3 class="text-xl font-black text-warm-text text-dark-text mb-2 tracking-tighter">No students found</h3>
                <p class="text-warm-muted text-dark-muted">
                    {{ searchQuery ? `No students found matching "${searchQuery}"` : 'No students found in this class' }}
                </p>
            </div>

            <!-- Pagination -->
            <div v-if="props.students && props.students.links && props.students.links.length > 3" class="mt-8 flex justify-center">
                <div class="flex flex-wrap gap-2">
                    <Link
                        v-for="(link, index) in props.students.links"
                        :key="index"
                        :href="link.url"
                        v-html="link.label"
                        class="px-3 py-2 text-sm border border-terracotta/20 rounded-lg transition-colors"
                        :class="{
                            'bg-terracotta text-white border-terracotta': link.active,
                            'text-warm-text text-dark-text hover:bg-terracotta/10': !link.active
                        }"
                    />
                </div>
            </div>
        </div>
    </Sidebar>
</template>