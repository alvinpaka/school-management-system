<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import NoticeModal from '@/Components/Modals/NoticeModal.vue';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { 
    ArrowLeft,
    Edit2,
    Trash2,
    Calendar,
    Tag,
    User,
    Bell,
    Megaphone,
    Clock
} from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    notice: Object,
    canManageNotices: Boolean
});

const showEditModal = ref(false);

const deleteNotice = () => {
    if (confirm('Are you sure you want to delete this notice?')) {
        router.delete(route('notices.destroy', props.notice.id));
    }
};

const getTypeIcon = (type) => {
    switch (type) {
        case 'holiday': return Calendar;
        case 'exam': return Bell;
        case 'meeting': return User;
        case 'event': return Calendar;
        default: return Megaphone;
    }
};

const getTypeColor = (type) => {
    switch (type) {
        case 'holiday': return 'bg-green-500';
        case 'exam': return 'bg-red-500';
        case 'meeting': return 'bg-blue-500';
        case 'policy': return 'bg-purple-500';
        case 'event': return 'bg-orange-500';
        default: return 'bg-gray-500';
    }
};

const getPriorityColor = (priority) => {
    switch (priority) {
        case 'high': return 'bg-destructive text-destructive-foreground';
        case 'medium': return 'bg-amber text-amber-foreground';
        default: return 'bg-terracotta text-white';
    }
};
</script>

<template>
    <Head :title="notice.title + ' | EduManage Pro'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Bell class="w-5 h-5 text-terracotta" />
                <span class="font-semibold text-warm-text dark:text-dark-text">Notice Details</span>
            </div>
        </template>

        <div class="mx-auto max-w-4xl space-y-6 animate-fade-in-up">
            <!-- Back Button -->
            <Link :href="route('notices.index')">
                <Button variant="ghost" class="hover:bg-terracotta/10 text-terracotta">
                    <ArrowLeft class="w-4 h-4 mr-2" />
                    Back to Notices
                </Button>
            </Link>

            <!-- Notice Card -->
            <div class="card-warm overflow-hidden">
                <!-- Header -->
                <div class="p-6 border-b border-terracotta/20">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <Badge :class="getPriorityColor(notice.priority)">
                                    {{ notice.priority }}
                                </Badge>
                                <Badge variant="outline" class="capitalize border-terracotta/20 text-warm-text dark:text-dark-text">
                                    {{ notice.type }}
                                </Badge>
                            </div>
                            <h1 class="text-3xl font-black text-warm-text dark:text-dark-text tracking-tighter">
                                {{ notice.title }}
                            </h1>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div v-if="canManageNotices" class="flex gap-2">
                            <Button variant="ghost" size="sm" @click="showEditModal = true" class="hover:bg-terracotta/10 text-terracotta">
                                <Edit2 class="w-4 h-4 mr-2" />
                                Edit
                            </Button>
                            <Button variant="ghost" size="sm" @click="deleteNotice" class="hover:bg-destructive/10 text-destructive">
                                <Trash2 class="w-4 h-4 mr-2" />
                                Delete
                            </Button>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="prose prose-lg max-w-none text-warm-text dark:text-dark-text leading-relaxed">
                        {{ notice.content }}
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="p-6 border-t border-terracotta/20 bg-warm-bg/50 dark:bg-dark-bg/50">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-center gap-2 text-warm-muted dark:text-dark-muted">
                            <Calendar class="w-4 h-4 text-terracotta" />
                            <span>Published: {{ notice.published_at }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-warm-muted dark:text-dark-muted">
                            <Clock class="w-4 h-4 text-terracotta" />
                            <span>Expires: {{ notice.expires_at }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-warm-muted dark:text-dark-muted">
                            <Tag class="w-4 h-4 text-terracotta" />
                            <span class="capitalize">Target: {{ notice.target_audience }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <NoticeModal
            v-model:open="showEditModal"
            :notice="notice"
            :is-editing="true"
            @close="showEditModal = false"
            @success="showEditModal = false"
        />
    </Sidebar>
</template>
