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
    Briefcase,
    Edit,
    Trash2,
    Calendar,
    MapPin,
    GraduationCap,
    BookOpen,
    Users,
    Clock,
    Award,
    MoreVertical,
    Download,
    Share2,
    FileText,
    Activity,
    UserCircle,
    Building,
    TrendingUp
} from 'lucide-vue-next';

const props = defineProps({
    teacher: {
        type: Object,
        required: true
    }
});

const activeTab = ref('overview');
const showDeleteDialog = ref(false);

const deleteTeacher = () => {
    showDeleteDialog.value = false;
    router.delete(route('teachers.destroy', props.teacher.id));
};

const getInitials = (name) => {
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
};

const formatDate = (date) => {
    if (!date) return 'Not specified';
    return new Date(date).toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
};
</script>

<template>
    <Head title="Teacher Details" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('teachers.index')">
                    <Button variant="ghost" size="sm" class="gap-2">
                        <ArrowLeft class="w-4 h-4" />
                        Teachers
                    </Button>
                </Link>
                <Separator orientation="vertical" class="h-6" />
                <span class="font-semibold">Teacher Profile</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Header Card with Teacher Photo and Quick Info -->
            <Card class="overflow-hidden">
                <div class="h-32 bg-emerald-500"></div>
                <CardContent class="relative pt-0 pb-6">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between -mt-16 md:-mt-20">
                        <!-- Teacher Avatar and Basic Info -->
                        <div class="flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-6">
                            <div class="relative">
                                <Avatar class="w-32 h-32 md:w-40 md:h-40 border-4 border-white dark:border-gray-950 shadow-xl">
                                    <AvatarImage 
                                        v-if="teacher.user?.photo"
                                        :src="`/storage/${teacher.user.photo}`" 
                                        :alt="teacher.user.name"
                                    />
                                    <AvatarFallback class="text-3xl md:text-4xl font-bold bg-emerald-500 text-white">
                                        {{ getInitials(teacher.user.name) }}
                                    </AvatarFallback>
                                </Avatar>
                                <div class="absolute bottom-2 right-2 w-5 h-5 bg-emerald-500 rounded-full border-4 border-white dark:border-gray-950"></div>
                            </div>
                            
                            <div class="text-center md:text-left space-y-2 mb-2">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ teacher.user.name }}
                                </h1>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                                    <Badge class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                        Faculty Member
                                    </Badge>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <Briefcase class="w-4 h-4 mr-1.5" />
                                        {{ teacher.employee_id }}
                                    </div>
                                    <div v-if="teacher.specialization" class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <GraduationCap class="w-4 h-4 mr-1.5" />
                                        {{ teacher.specialization }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-center md:justify-end space-x-2 mt-4 md:mt-0">
                            <Link :href="route('teachers.edit', teacher.id)">
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
                                        Delete Teacher
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6">
                <Card class="hover:shadow-md transition-shadow duration-200">
                    <CardContent class="p-4 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Classes</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ teacher.classes?.length || 0 }}</p>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0 ml-3">
                                <BookOpen class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card class="hover:shadow-md transition-shadow duration-200">
                    <CardContent class="p-4 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Students</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ teacher.total_students || 0 }}</p>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center flex-shrink-0 ml-3">
                                <Users class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card class="hover:shadow-md transition-shadow duration-200">
                    <CardContent class="p-4 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Experience</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ teacher.experience ? teacher.experience + (teacher.experience.includes('year') ? '' : ' yrs') : '0 yrs' }}</p>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center flex-shrink-0 ml-3">
                                <Award class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600 dark:text-purple-400" />
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
                    <TabsTrigger value="professional" class="gap-2">
                        <div class="flex items-center justify-center">
                            <Briefcase class="w-4 h-4" />
                        </div>
                        <span class="hidden sm:inline">Professional</span>
                    </TabsTrigger>
                    <TabsTrigger value="schedule" class="gap-2">
                        <div class="flex items-center justify-center">
                            <Clock class="w-4 h-4" />
                        </div>
                        <span class="hidden sm:inline">Schedule</span>
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
                                <CardDescription>Basic teacher details and contact information</CardDescription>
                            </CardHeader>
                            <CardContent class="space-y-4">
                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <Mail class="w-4 h-4" />
                                        Email
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ teacher.user.email }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <Phone class="w-4 h-4" />
                                        Phone
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ teacher.phone || 'Not specified' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <Calendar class="w-4 h-4" />
                                        Date of Birth
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ formatDate(teacher.date_of_birth) }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <UserCircle class="w-4 h-4" />
                                        Gender
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ teacher.gender || 'Not specified' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <MapPin class="w-4 h-4" />
                                        Address
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ teacher.address || 'Not specified' }}
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Emergency Contact -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <Phone class="w-5 h-5" />
                                    Emergency Contact
                                </CardTitle>
                                <CardDescription>Emergency contact information</CardDescription>
                            </CardHeader>
                            <CardContent class="space-y-4">
                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        Contact Name
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ teacher.emergency_contact_name || 'Not specified' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        Phone Number
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ teacher.emergency_contact_phone || 'Not specified' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        Relationship
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ teacher.emergency_contact_relationship || 'Not specified' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3">
                                    <div class="col-span-1 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        Blood Group
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ teacher.blood_group || 'Not specified' }}
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>

                <!-- Professional Tab -->
                <TabsContent value="professional" class="space-y-6 mt-6">
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Briefcase class="w-5 h-5" />
                                Professional Details
                            </CardTitle>
                            <CardDescription>Teacher's professional information and qualifications</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Employee ID
                                        </div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ teacher.employee_id }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Specialization
                                        </div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ teacher.specialization || 'Not specified' }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Qualification
                                        </div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ teacher.qualification || 'Not specified' }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 py-3">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Department
                                        </div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ teacher.specialization || 'Not assigned' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Joining Date
                                        </div>
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ formatDate(teacher.joining_date) }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Experience
                                        </div>
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ teacher.experience || 'Not specified' }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Employment Type
                                        </div>
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ teacher.employment_type || 'Not specified' }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 py-3">
                                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Status
                                        </div>
                                        <div>
                                            <Badge 
                                                :class="{
                                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800': teacher.status === 'Active',
                                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800': teacher.status === 'Inactive',
                                                    'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800': teacher.status === 'Suspended',
                                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800': teacher.status === 'On Leave'
                                                }"
                                            >
                                                {{ teacher.status || 'Not specified' }}
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Subjects Teaching -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <BookOpen class="w-5 h-5" />
                                Subjects Teaching
                            </CardTitle>
                            <CardDescription>Current subjects assigned to this teacher</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <BookOpen class="w-12 h-12 mx-auto mb-3 opacity-50" />
                                <p>No subjects assigned yet</p>
                                <Button variant="link" class="mt-2">Assign Subjects</Button>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <!-- Schedule Tab -->
                <TabsContent value="schedule" class="space-y-6 mt-6">
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Clock class="w-5 h-5" />
                                Class Schedule
                            </CardTitle>
                            <CardDescription>Weekly teaching schedule and timetable</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <Clock class="w-12 h-12 mx-auto mb-3 opacity-50" />
                                <p>No schedule available</p>
                                <Button variant="link" class="mt-2">Create Schedule</Button>
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
                            <CardDescription>Teacher's recent activities and system interactions</CardDescription>
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
                    <DialogTitle>Delete Teacher</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete <strong>{{ teacher.user.name }}</strong>? 
                        This action cannot be undone and will permanently remove all teacher data.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="showDeleteDialog = false">
                        Cancel
                    </Button>
                    <Button variant="destructive" @click="deleteTeacher">
                        Delete Teacher
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </Sidebar>
</template>