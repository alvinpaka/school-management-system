<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
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
    MapPin,
    Edit,
    Trash2,
    Users,
    Home,
    Briefcase,
    Heart,
    MoreVertical,
    Download,
    Share2,
    FileText,
    Activity,
    UserCircle,
    Baby,
    GraduationCap
} from 'lucide-vue-next';

const props = defineProps({
    parent: {
        type: Object,
        required: true
    }
});

const activeTab = ref('overview');
const showDeleteDialog = ref(false);

const editRoute = computed(() => {
    if (!props.parent?.id) return '#';
    return route('parents.edit', { parent: props.parent.id });
});

const deleteRoute = computed(() => {
    if (!props.parent?.id) return '#';
    return route('parents.destroy', { parent: props.parent.id });
});

const deleteParent = () => {
    showDeleteDialog.value = false;
    router.delete(deleteRoute.value);
};

const getInitials = (name) => {
    if (!name) return 'PA';
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
};

const getRelationshipBadgeClass = (relationship) => {
    switch (relationship?.toLowerCase()) {
        case 'father':
            return 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800';
        case 'mother':
            return 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400 border-pink-200 dark:border-pink-800';
        case 'guardian':
            return 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 border-purple-200 dark:border-purple-800';
        default:
            return 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400 border-gray-200 dark:border-gray-800';
    }
};

const sendEmail = () => {
    if (props.parent.user?.email) {
        window.location.href = `mailto:${props.parent.user.email}`;
    }
};

const callParent = () => {
    if (props.parent.phone) {
        window.location.href = `tel:${props.parent.phone}`;
    }
};

const scheduleMeeting = () => {
    // For now, show a toast/notification that meeting scheduling is coming soon
    alert('Meeting scheduling feature coming soon!');
    // TODO: Navigate to meeting scheduling page when implemented
    // router.get(route('meetings.create', { parent_id: props.parent.id }));
};

const sendReportCard = () => {
    if (props.parent.students?.length > 0) {
        // Navigate to report cards index page for now
        router.get(route('report-cards.index'));
    } else {
        alert('No children linked to this parent');
    }
};
</script>

<template>
    <Head title="Parent Details" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('parents.index')">
                    <Button variant="ghost" size="sm" class="gap-2">
                        <ArrowLeft class="w-4 h-4" />
                        Parents
                    </Button>
                </Link>
                <Separator orientation="vertical" class="h-6" />
                <span class="font-semibold">Parent Profile</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Header Card with Parent Photo and Quick Info -->
            <Card class="overflow-hidden">
                <div class="h-32 bg-purple-500"></div>
                <CardContent class="relative pt-0 pb-6">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between -mt-16 md:-mt-20">
                        <!-- Parent Avatar and Basic Info -->
                        <div class="flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-6">
                            <div class="relative">
                                <Avatar class="w-32 h-32 md:w-40 md:h-40 border-4 border-white dark:border-gray-950 shadow-xl">
                                    <AvatarImage 
                                        v-if="parent.user?.photo"
                                        :src="`/storage/${parent.user.photo}`" 
                                        :alt="parent.user?.name"
                                    />
                                    <AvatarFallback class="text-3xl md:text-4xl font-bold bg-purple-500 text-white">
                                        {{ getInitials(parent.user?.name) }}
                                    </AvatarFallback>
                                </Avatar>
                                <div class="absolute bottom-2 right-2 w-5 h-5 bg-emerald-500 rounded-full border-4 border-white dark:border-gray-950"></div>
                            </div>
                            
                            <div class="text-center md:text-left space-y-2 mb-2">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ parent.user?.name || 'Unknown Parent' }}
                                </h1>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                                    <Badge :class="getRelationshipBadgeClass(parent.relationship_to_student)" class="border">
                                        {{ parent.relationship_to_student || 'Parent/Guardian' }}
                                    </Badge>
                                    <div v-if="parent.occupation" class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <Briefcase class="w-4 h-4 mr-1.5" />
                                        {{ parent.occupation }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-center md:justify-end space-x-2 mt-4 md:mt-0">
                            <Link :href="editRoute">
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
                                        Delete Parent
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
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Children</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ parent.students?.length || 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                                <Baby class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Email</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ parent.user?.email || 'Not specified' }}</p>
                            </div>
                            <div class="w-12 h-12 bg-pink-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center">
                                <Mail class="w-6 h-6 text-pink-600 dark:text-pink-400" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Phone</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ parent.phone || 'Not specified' }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                <Phone class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">Active</p>
                            </div>
                            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center">
                                <Heart class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
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
                    <TabsTrigger value="children" class="gap-2">
                        <div class="flex items-center justify-center">
                            <Baby class="w-4 h-4" />
                        </div>
                        <span class="hidden sm:inline">Children</span>
                    </TabsTrigger>
                    <TabsTrigger value="communication" class="gap-2">
                        <div class="flex items-center justify-center">
                            <Mail class="w-4 h-4" />
                        </div>
                        <span class="hidden sm:inline">Communication</span>
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
                                <CardDescription>Basic parent details and contact information</CardDescription>
                            </CardHeader>
                            <CardContent class="space-y-4">
                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <Mail class="w-4 h-4" />
                                        Email
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ parent.user?.email || 'Not specified' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <Phone class="w-4 h-4" />
                                        Phone
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ parent.phone || 'Not specified' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <MapPin class="w-4 h-4" />
                                        Address
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ parent.address || 'Not specified' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <Briefcase class="w-4 h-4" />
                                        Occupation
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ parent.occupation || 'Not specified' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-3">
                                    <div class="col-span-1 flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <Heart class="w-4 h-4" />
                                        Relationship
                                    </div>
                                    <div class="col-span-2">
                                        <Badge :class="getRelationshipBadgeClass(parent.relationship_to_student)" class="border">
                                            {{ parent.relationship_to_student || 'Not specified' }}
                                        </Badge>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Contact Actions -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <Mail class="w-5 h-5" />
                                    Quick Actions
                                </CardTitle>
                                <CardDescription>Contact and communication options</CardDescription>
                            </CardHeader>
                            <CardContent class="space-y-3">
                                <Button 
                                    class="w-full" 
                                    variant="outline"
                                    @click="sendEmail"
                                    :disabled="!parent.user?.email"
                                >
                                    <Mail class="w-4 h-4 mr-2" />
                                    Send Email
                                </Button>
                                <Button 
                                    class="w-full" 
                                    variant="outline"
                                    @click="callParent"
                                    :disabled="!parent.phone"
                                >
                                    <Phone class="w-4 h-4 mr-2" />
                                    Call Parent
                                </Button>
                                <Button 
                                    class="w-full" 
                                    variant="outline"
                                    @click="scheduleMeeting"
                                >
                                    <Users class="w-4 h-4 mr-2" />
                                    Schedule Meeting
                                </Button>
                                <Button 
                                    class="w-full" 
                                    variant="outline"
                                    @click="sendReportCard"
                                    :disabled="!parent.students?.length"
                                >
                                    <FileText class="w-4 h-4 mr-2" />
                                    Send Report Card
                                </Button>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>

                <!-- Children Tab -->
                <TabsContent value="children" class="space-y-6 mt-6">
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Baby class="w-5 h-5" />
                                Children Information
                            </CardTitle>
                            <CardDescription>Students under this parent's guardianship</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div v-if="parent.students && parent.students.length > 0" class="space-y-4">
                                <div 
                                    v-for="student in parent.students" 
                                    :key="student.id"
                                    class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                >
                                    <div class="flex items-center gap-4">
                                        <Avatar class="w-12 h-12">
                                            <AvatarImage 
                                                v-if="student.user?.photo"
                                                :src="`/storage/${student.user.photo}`" 
                                                :alt="student.user?.name"
                                            />
                                            <AvatarFallback class="bg-blue-500 text-white font-medium">
                                                {{ getInitials(student.user?.name) }}
                                            </AvatarFallback>
                                        </Avatar>
                                        <div>
                                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                                {{ student.user?.name || 'Unknown Student' }}
                                            </h3>
                                            <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="flex items-center gap-1">
                                                    <GraduationCap class="w-3 h-3" />
                                                    {{ student.admission_number || 'N/A' }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <Users class="w-3 h-3" />
                                                    {{ student.academic_class?.name || 'No Class' }}
                                                </span>
                                                <span v-if="student.section" class="flex items-center gap-1">
                                                    <UserCircle class="w-3 h-3" />
                                                    {{ student.section.name }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Link :href="route('students.show', student.id)">
                                            <Button variant="outline" size="sm">
                                                <User class="w-4 h-4 mr-2" />
                                                View Profile
                                            </Button>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <GraduationCap class="w-12 h-12 mx-auto mb-3 opacity-50" />
                                <p>No children linked yet</p>
                                <Link :href="editRoute">
                                    <Button variant="link" class="mt-2">Link Student</Button>
                                </Link>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <!-- Communication Tab -->
                <TabsContent value="communication" class="space-y-6 mt-6">
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Mail class="w-5 h-5" />
                                Communication History
                            </CardTitle>
                            <CardDescription>Messages and interactions with this parent</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <Mail class="w-12 h-12 mx-auto mb-3 opacity-50" />
                                <p>No communication history</p>
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
                            <CardDescription>Parent's recent activities and system interactions</CardDescription>
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
                    <DialogTitle>Delete Parent</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete <strong>{{ parent.user?.name }}</strong>? 
                        This action cannot be undone and will permanently remove all parent data.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="showDeleteDialog = false">
                        Cancel
                    </Button>
                    <Button variant="destructive" @click="deleteParent">
                        Delete Parent
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </Sidebar>
</template>