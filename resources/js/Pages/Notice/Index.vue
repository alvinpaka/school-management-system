<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import NoticeModal from '@/Components/Modals/NoticeModal.vue';
import DeleteConfirmModal from '@/Components/Modals/DeleteConfirmModal.vue';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { 
    Plus,
    Search,
    Filter,
    Eye,
    Bell,
    AlertTriangle,
    Calendar,
    Tag,
    Edit2,
    Trash2
} from 'lucide-vue-next';

const props = defineProps({
    notices: Array,
    canManageNotices: Boolean
});

// Modal state
const showModal = ref(false);
const isEditing = ref(false);
const isViewing = ref(false);
const selectedNotice = ref(null);

// Delete modal state
const showDeleteModal = ref(false);
const noticeToDelete = ref(null);
const isDeleting = ref(false);

const openCreateModal = () => {
    isEditing.value = false;
    isViewing.value = false;
    selectedNotice.value = null;
    showModal.value = true;
};

const openViewModal = (notice) => {
    isEditing.value = false;
    isViewing.value = true;
    selectedNotice.value = notice;
    showModal.value = true;
};

const openEditModal = (notice) => {
    isEditing.value = true;
    isViewing.value = false;
    selectedNotice.value = notice;
    showModal.value = true;
};

const openDeleteModal = (notice) => {
    noticeToDelete.value = notice;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    if (!noticeToDelete.value) return;
    
    isDeleting.value = true;
    router.delete(route('notices.destroy', noticeToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            noticeToDelete.value = null;
            isDeleting.value = false;
        },
        onError: () => {
            isDeleting.value = false;
        }
    });
};

const searchQuery = ref('');
const selectedType = ref('all');
const selectedPriority = ref('all');
</script>

<template>
    <Head title="Notice Management | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Bell class="w-5 h-5 text-terracotta" />
                <span class="font-semibold text-warm-text text-dark-text">Notices</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 animate-fade-in-up">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter mb-2">Notices</h1>
                    <p class="text-warm-muted text-dark-muted font-medium">Manage school notices and announcements</p>
                </div>
                <div v-if="canManageNotices" class="flex gap-2">
                    <Button class="accent-terracotta text-white" @click="openCreateModal">
                        <Plus class="w-4 h-4 mr-2" />
                        Add Notice
                    </Button>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="card-warm">
                <div class="p-6 border-b border-terracotta/20">
                    <h3 class="text-lg font-black text-warm-text text-dark-text">Search & Filter</h3>
                    <p class="text-sm text-warm-muted text-dark-muted mt-1">Find notices quickly</p>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-warm-muted text-dark-muted w-4 h-4" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search notices by title or content..."
                                    class="w-full pl-10 pr-4 py-2 border border-terracotta/20 rounded-lg focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta bg-white bg-dark-bg text-warm-text text-dark-text"
                                />
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <select v-model="selectedType" class="px-4 py-2 border border-terracotta/20 rounded-lg focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta bg-white bg-dark-bg text-warm-text text-dark-text">
                                <option value="all">All Types</option>
                                <option value="holiday">Holiday</option>
                                <option value="meeting">Meeting</option>
                                <option value="exam">Exam</option>
                                <option value="policy">Policy</option>
                                <option value="event">Event</option>
                            </select>
                            <select v-model="selectedPriority" class="px-4 py-2 border border-terracotta/20 rounded-lg focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta bg-white bg-dark-bg text-warm-text text-dark-text">
                                <option value="all">All Priorities</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                            <Button variant="outline" class="border-terracotta/20 text-warm-muted text-dark-muted hover:text-terracotta">
                                <Filter class="w-4 h-4 mr-2" />
                                Filter
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notices List -->
            <div class="space-y-4">
                <div v-for="notice in notices" :key="notice.id" class="card-warm">
                    <div class="p-6 border-b border-terracotta/20">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-xl font-black text-warm-text text-dark-text tracking-tighter">{{ notice.title }}</h3>
                                <p class="text-sm text-warm-muted text-dark-muted mt-1">Published {{ new Date(notice.published_at).toLocaleDateString() }}</p>
                            </div>
                            <div class="flex gap-2">
                                <Badge :class="notice.priority === 'high' ? 'bg-destructive/10 text-destructive border-0' : notice.priority === 'medium' ? 'bg-amber/10 text-amber border-0' : 'bg-terracotta/10 text-terracotta border-0'">
                                    {{ notice.priority }}
                                </Badge>
                                <Badge variant="outline" class="capitalize border-terracotta/20 text-warm-text text-dark-text">
                                    {{ notice.type }}
                                </Badge>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-sm max-w-none text-warm-muted text-dark-muted">
                            {{ notice.content }}
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-terracotta/20">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-sm text-warm-muted text-dark-muted">
                                <div class="flex items-center">
                                    <Calendar class="w-4 h-4 mr-2 text-terracotta" />
                                    <span>Expires: {{ new Date(notice.expires_at).toLocaleDateString() }}</span>
                                </div>
                                <div class="flex items-center">
                                    <Tag class="w-4 h-4 mr-2 text-terracotta" />
                                    <span>{{ notice.target_audience }}</span>
                                </div>
                            </div>
                            <div class="mt-4 flex gap-2">
                                <Button variant="ghost" size="sm" class="hover:bg-terracotta/10 hover:text-terracotta text-terracotta" @click="openViewModal(notice)">
                                    <Eye class="w-4 h-4 mr-2" />
                                    Read More
                                </Button>
                                <template v-if="canManageNotices">
                                    <Button variant="ghost" size="sm" class="hover:bg-terracotta/10 hover:text-terracotta text-terracotta" @click="openEditModal(notice)">
                                        <Edit2 class="w-4 h-4 mr-2" />
                                        Edit
                                    </Button>
                                    <Button variant="ghost" size="sm" class="hover:bg-red-100 hover:text-red-600 text-red-600 dark:hover:bg-red-900/20 dark:hover:text-red-400" @click="openDeleteModal(notice)">
                                        <Trash2 class="w-4 h-4 mr-2" />
                                        Delete
                                    </Button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="notices.length === 0" class="card-warm">
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-terracotta/5 rounded-3xl flex items-center justify-center mx-auto mb-4">
                        <Bell class="w-10 h-10 text-terracotta/30" />
                    </div>
                    <h3 class="text-xl font-black text-warm-text text-dark-text mb-2 tracking-tighter">No Notices Found</h3>
                    <p class="text-warm-muted text-dark-muted">No notices have been published yet.</p>
                </div>
            </div>
        </div>

        <!-- Notice Modal for Create/Edit/View -->
        <NoticeModal
            v-model:open="showModal"
            :notice="selectedNotice"
            :is-editing="isEditing"
            :is-viewing="isViewing"
            @close="showModal = false"
            @success="showModal = false"
            @edit="openEditModal(selectedNotice)"
        />

        <!-- Delete Confirmation Modal -->
        <DeleteConfirmModal
            v-model:open="showDeleteModal"
            title="Delete Notice"
            description="Are you sure you want to delete this notice? This action cannot be undone."
            :item-name="noticeToDelete?.title"
            :processing="isDeleting"
            @confirm="confirmDelete"
            @cancel="noticeToDelete = null"
        />
    </Sidebar>
</template>