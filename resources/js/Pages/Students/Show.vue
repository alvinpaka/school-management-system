<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Separator } from '@/components/ui/separator';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { 
    ArrowLeft,
    User,
    Mail,
    Phone,
    Calendar,
    MapPin,
    Edit,
    Trash2,
    GraduationCap,
    Home,
    Users,
    BookOpen,
    ClipboardList,
    Download,
    Share2,
    MoreVertical,
    UserCircle,
    Briefcase,
    Heart,
    Activity,
    FileText,
    Award,
    DollarSign,
    History
} from 'lucide-vue-next';

const props = defineProps({
    student: {
        type: Object,
        required: true
    }
});

const activeTab = ref('overview');
const showDeleteDialog = ref(false);

const deleteStudent = () => {
    showDeleteDialog.value = false;
    router.delete(route('students.destroy', props.student.id));
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'active':
            return 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800';
        case 'inactive':
            return 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border-red-200 dark:border-red-800';
        case 'graduated':
            return 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800';
        case 'transferred':
            return 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200 dark:border-amber-800';
        default:
            return 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400 border-gray-200 dark:border-gray-800';
    }
};

const getStatusText = (status) => {
    const statusMap = {
        'active': 'Active Student',
        'inactive': 'Inactive',
        'graduated': 'Graduated',
        'transferred': 'Transferred'
    };
    return statusMap[status] || 'Unknown';
};

const getInitials = (name) => {
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
};

const getParentInfo = () => {
    if (props.student.parent_user?.user) {
        return {
            name: props.student.parent_user.user.name,
            email: props.student.parent_user.user.email,
            phone: props.student.parent_user.phone,
            occupation: props.student.parent_user.occupation,
            relationship: props.student.parent_user.relationship_to_student
        };
    }
    
    return {
        name: props.student.parent_name,
        email: props.student.parent_email,
        phone: props.student.parent_phone,
        occupation: 'Not specified',
        relationship: 'Parent/Guardian'
    };
};

const formatDate = (date) => {
    if (!date) return 'Not specified';
    return new Date(date).toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
};

const calculateAge = (dob) => {
    if (!dob) return 'N/A';
    const today = new Date();
    const birthDate = new Date(dob);
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
};

const getAcademicYear = (admissionDate) => {
    if (!admissionDate) return 'Not specified';
    const date = new Date(admissionDate);
    const year = date.getFullYear();
    const nextYear = year + 1;
    return `${year}-${nextYear}`;
};
</script>

<template>
    <Head title="Student Details" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('students.index')">
                    <Button variant="ghost" size="sm" class="gap-2">
                        <ArrowLeft class="w-4 h-4" />
                        Students
                    </Button>
                </Link>
                <Separator orientation="vertical" class="h-6" />
                <span class="font-semibold">Student Profile</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Header Card with Student Photo and Quick Info -->
            <Card class="overflow-hidden">
                <div class="h-32 bg-blue-500"></div>
                <CardContent class="relative pt-0 pb-6">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between -mt-16 md:-mt-20">
                        <!-- Student Avatar and Basic Info -->
                        <div class="flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-6">
                            <div class="relative">
                                <Avatar class="w-32 h-32 md:w-40 md:h-40 border-4 border-white dark:border-gray-950 shadow-xl">
                                    <AvatarImage 
                                        v-if="student.user?.photo"
                                        :src="`/storage/${student.user.photo}`" 
                                        :alt="student.user.name"
                                    />
                                    <AvatarFallback class="text-3xl md:text-4xl font-bold bg-blue-500 text-white">
                                        {{ getInitials(student.user.name) }}
                                    </AvatarFallback>
                                </Avatar>
                                <div class="absolute bottom-2 right-2 w-5 h-5 bg-emerald-500 rounded-full border-4 border-white dark:border-gray-950"></div>
                            </div>
                            
                            <div class="text-center md:text-left space-y-2 mb-2">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ student.user.name }}
                                </h1>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                                    <Badge :class="getStatusBadgeClass(student.status)" class="border">
                                        {{ getStatusText(student.status) }}
                                    </Badge>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <GraduationCap class="w-4 h-4 mr-1.5" />
                                        {{ student.academic_class?.name || 'No Class' }} 
                                        <span v-if="student.section?.name" class="ml-1">- {{ student.section.name }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <UserCircle class="w-4 h-4 mr-1.5" />
                                        {{ student.admission_number }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-center md:justify-end space-x-2 mt-4 md:mt-0">
                            <Link :href="route('students.edit', student.id)">
                                <Button variant="default" size="sm" class="gap-2">
                                    <Edit class="w-4 h-4" />
                                    Edit Profile
                                </Button>
                            </Link>
                            
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="outline" size="sm">
                                        <MoreVertical class="w-4 h-4" />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end" class="w-48">
                                    <DropdownMenuItem>
                                        <Download class="w-4 h-4 mr-2" />
                                        Download Report
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        <Share2 class="w-4 h-4 mr-2" />
                                        Share Profile
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        <FileText class="w-4 h-4 mr-2" />
                                        View Documents
                                    </DropdownMenuItem>
                                    <Separator class="my-1" />
                                    <DropdownMenuItem 
                                        @click="showDeleteDialog = true" 
                                        class="text-red-600 focus:text-red-600"
                                    >
                                        <Trash2 class="w-4 h-4 mr-2" />
                                        Delete Student
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Attendance</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">95.5%</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                <ClipboardList class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Overall Grade</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">A-</p>
                            </div>
                            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center">
                                <Award class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Assignments</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">12/15</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                                <BookOpen class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Fee Status</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">Paid</p>
                            </div>
                            <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
                                <DollarSign class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Main Content with Tabs -->
            <Tabs v-model="activeTab" default-value="overview" class="w-full">
                <TabsList class="grid w-full grid-cols-4 lg:w-auto lg:inline-grid">
                    <TabsTrigger value="overview" class="gap-2">
                        <div class="flex items-center justify-center">
                            <User class="w-4 h-4" />
                        </div>
                        <span class="hidden sm:inline">Overview</span>
                    </TabsTrigger>
                    <TabsTrigger value="academic" class="gap-2">
                        <div class="flex items-center justify-center">
                            <GraduationCap class="w-4 h-4" />
                        </div>
                        <span class="hidden sm:inline">Academic</span>
                    </TabsTrigger>
                    <TabsTrigger value="parent" class="gap-2">
                        <div class="flex items-center justify-center">
                            <Users class="w-4 h-4" />
                        </div>
                        <span class="hidden sm:inline">Parent/Guardian</span>
                    </TabsTrigger>
                    <TabsTrigger value="activity" class="gap-2">
                        <div class="flex items-center justify-center">
                            <Activity class="w-4 h-4" />
                        </div>
                        <span class="hidden sm:inline">Activity</span>
                    </TabsTrigger>
                </TabsList>

                <!-- Overview Tab -->
                <TabsContent value="overview" class="space-y-6 mt-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <User class="w-5 h-5" />
                                    Personal Information
                                </CardTitle>
                                <CardDescription>Basic student details and contact information</CardDescription>
                            </CardHeader>
                            <CardContent class="space-y-4">
                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <Mail class="w-4 h-4" />
                                        Email
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ student.user.email }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <Phone class="w-4 h-4" />
                                        Phone
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ student.phone || 'Not specified' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <Calendar class="w-4 h-4" />
                                        Date of Birth
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ formatDate(student.date_of_birth) }}
                                        <Badge v-if="student.date_of_birth" variant="outline" class="ml-2">
                                            {{ calculateAge(student.date_of_birth) }} years
                                        </Badge>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <UserCircle class="w-4 h-4" />
                                        Gender
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ student.gender || 'Not specified' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <MapPin class="w-4 h-4" />
                                        Address
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ student.address || 'Not specified' }}
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Medical Information -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <Heart class="w-5 h-5" />
                                    Health & Medical
                                </CardTitle>
                                <CardDescription>Medical history and health information</CardDescription>
                            </CardHeader>
                            <CardContent class="space-y-4">
                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        Blood Group
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ student.blood_group || 'Not specified' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        Medical Conditions
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ student.medical_conditions || 'None reported' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3">
                                    <div class="col-span-1 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        Emergency Contact
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ student.emergency_contact || 'Not specified' }}
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>

                <!-- Academic Tab -->
                <TabsContent value="academic" class="space-y-6 mt-6">
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <GraduationCap class="w-5 h-5" />
                                Academic Details
                            </CardTitle>
                            <CardDescription>Student's academic information and enrollment details</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Admission Number
                                        </div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ student.admission_number }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Roll Number
                                        </div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ student.roll_number }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Class
                                        </div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ student.academic_class?.name || 'Not assigned' }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 py-3">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Section
                                        </div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ student.section?.name || 'Not assigned' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Admission Date
                                        </div>
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ formatDate(student.admission_date) }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Academic Year
                                        </div>
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ getAcademicYear(student.admission_date) }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Status
                                        </div>
                                        <div>
                                            <Badge :class="getStatusBadgeClass(student.status)" class="border">
                                                {{ getStatusText(student.status) }}
                                            </Badge>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 py-3">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Transport
                                        </div>
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            Not specified
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Academic History -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <History class="w-5 h-5" />
                                Academic History
                            </CardTitle>
                            <CardDescription>Previous educational background and transfer information</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Previous School
                                        </div>
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ student.previous_school || 'Not specified' }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 py-3">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Transfer Certificate
                                        </div>
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ student.transfer_certificate || 'Not specified' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Recent Performance -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <BookOpen class="w-5 h-5" />
                                Recent Academic Performance
                            </CardTitle>
                            <CardDescription>Latest exam results and subject performance</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <FileText class="w-12 h-12 mx-auto mb-3 opacity-50" />
                                <p>No exam results available yet</p>
                                <Button variant="link" class="mt-2">Add Results</Button>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <!-- Parent/Guardian Tab -->
                <TabsContent value="parent" class="space-y-6 mt-6">
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Users class="w-5 h-5" />
                                Parent/Guardian Information
                            </CardTitle>
                            <CardDescription>Primary contact and guardian details</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                                <Avatar class="w-16 h-16">
                                    <AvatarImage 
                                        v-if="props.student.parent_user?.user?.photo"
                                        :src="`/storage/${props.student.parent_user.user.photo}`" 
                                        :alt="getParentInfo().name"
                                    />
                                    <AvatarFallback class="bg-orange-500 text-white text-lg font-bold">
                                        {{ getInitials(getParentInfo().name || 'N/A') }}
                                    </AvatarFallback>
                                </Avatar>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white">
                                        {{ getParentInfo().name || 'Not specified' }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ getParentInfo().relationship || 'Parent/Guardian' }}
                                    </p>
                                </div>
                            </div>

                            <Separator />

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                            <Mail class="w-4 h-4" />
                                            Email
                                        </div>
                                        <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                            {{ getParentInfo().email || 'Not specified' }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                            <Phone class="w-4 h-4" />
                                            Phone
                                        </div>
                                        <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                            {{ getParentInfo().phone || 'Not specified' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                            <Briefcase class="w-4 h-4" />
                                            Occupation
                                        </div>
                                        <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                            {{ getParentInfo().occupation || 'Not specified' }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                            <UserCircle class="w-4 h-4" />
                                            Relationship
                                        </div>
                                        <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                            {{ getParentInfo().relationship || 'Not specified' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <Separator />

                            <div class="flex items-center justify-end space-x-2 pt-2">
                                <Button variant="outline" size="sm">
                                    <Mail class="w-4 h-4 mr-2" />
                                    Send Email
                                </Button>
                                <Button variant="outline" size="sm">
                                    <Phone class="w-4 h-4 mr-2" />
                                    Call Parent
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <!-- Activity Tab -->
                <TabsContent value="activity" class="space-y-6 mt-6">
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Activity class="w-5 h-5" />
                                Recent Activity
                            </CardTitle>
                            <CardDescription>Student's recent activities and system interactions</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <Activity class="w-12 h-12 mx-auto mb-3 opacity-50" />
                                <p>No recent activity to display</p>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>
            </Tabs>
        </div>

        <!-- Delete Confirmation Dialog -->
        <Dialog v-model:open="showDeleteDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Delete Student</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete <strong>{{ student.user.name }}</strong>? 
                        This action cannot be undone and will permanently remove all student data.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="showDeleteDialog = false">
                        Cancel
                    </Button>
                    <Button variant="destructive" @click="deleteStudent">
                        Delete Student
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </Sidebar>
</template>

<style scoped>
/* Add any custom styles here if needed */
</style>