<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
    Plus,
    Search,
    Filter,
    Eye,
    Bell,
    AlertTriangle,
    Calendar,
    Tag
} from 'lucide-vue-next';

defineProps({
    notices: Array
});

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
                <span class="font-semibold text-warm-text dark:text-dark-text">Notices</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 animate-fade-in-up">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-warm-text dark:text-dark-text tracking-tighter mb-2">Notices</h1>
                    <p class="text-warm-muted dark:text-dark-muted font-medium">Manage school notices and announcements</p>
                </div>
                <div class="flex gap-2">
                    <Button class="accent-terracotta text-white">
                        <Plus class="w-4 h-4 mr-2" />
                        Add Notice
                    </Button>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="card-warm">
                <div class="p-6 border-b border-terracotta/20">
                    <h3 class="text-lg font-black text-warm-text dark:text-dark-text">Search & Filter</h3>
                    <p class="text-sm text-warm-muted dark:text-dark-muted mt-1">Find notices quickly</p>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-warm-muted dark:text-dark-muted w-4 h-4" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search notices by title or content..."
                                    class="w-full pl-10 pr-4 py-2 border border-terracotta/20 rounded-lg focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta bg-white dark:bg-dark-bg text-warm-text dark:text-dark-text"
                                />
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <select v-model="selectedType" class="px-4 py-2 border border-terracotta/20 rounded-lg focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta bg-white dark:bg-dark-bg text-warm-text dark:text-dark-text">
                                <option value="all">All Types</option>
                                <option value="holiday">Holiday</option>
                                <option value="meeting">Meeting</option>
                                <option value="exam">Exam</option>
                                <option value="policy">Policy</option>
                                <option value="event">Event</option>
                            </select>
                            <select v-model="selectedPriority" class="px-4 py-2 border border-terracotta/20 rounded-lg focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta bg-white dark:bg-dark-bg text-warm-text dark:text-dark-text">
                                <option value="all">All Priorities</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                            <Button variant="outline" class="border-terracotta/20 text-warm-muted dark:text-dark-muted hover:text-terracotta">
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
                                <h3 class="text-xl font-black text-warm-text dark:text-dark-text tracking-tighter">{{ notice.title }}</h3>
                                <p class="text-sm text-warm-muted dark:text-dark-muted mt-1">Published {{ new Date(notice.published_at).toLocaleDateString() }}</p>
                            </div>
                            <div class="flex gap-2">
                                <Badge :class="notice.priority === 'high' ? 'bg-destructive/10 text-destructive border-0' : notice.priority === 'medium' ? 'bg-amber/10 text-amber border-0' : 'bg-terracotta/10 text-terracotta border-0'">
                                    {{ notice.priority }}
                                </Badge>
                                <Badge variant="outline" class="capitalize border-terracotta/20 text-warm-text dark:text-dark-text">
                                    {{ notice.type }}
                                </Badge>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-sm max-w-none text-warm-muted dark:text-dark-muted">
                            {{ notice.content }}
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-terracotta/20">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-sm text-warm-muted dark:text-dark-muted">
                                <div class="flex items-center">
                                    <Calendar class="w-4 h-4 mr-2 text-terracotta" />
                                    <span>Expires: {{ new Date(notice.expires_at).toLocaleDateString() }}</span>
                                </div>
                                <div class="flex items-center">
                                    <Tag class="w-4 h-4 mr-2 text-terracotta" />
                                    <span>{{ notice.target_audience }}</span>
                                </div>
                            </div>
                            <div class="mt-4">
                                <Link :href="route('notice.show', notice.id)">
                                    <Button variant="ghost" size="sm" class="hover:bg-terracotta/10 text-terracotta">
                                        <Eye class="w-4 h-4 mr-2" />
                                        Read More
                                    </Button>
                                </Link>
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
                    <h3 class="text-xl font-black text-warm-text dark:text-dark-text mb-2 tracking-tighter">No Notices Found</h3>
                    <p class="text-warm-muted dark:text-dark-muted">No notices have been published yet.</p>
                </div>
            </div>
        </div>
    </Sidebar>
</template>