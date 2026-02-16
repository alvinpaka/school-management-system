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
    <Head title="Notice Management" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Bell class="w-5 h-5" />
                <span>Notices</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Notices</h2>
                        <p class="text-gray-600 dark:text-gray-400">Manage school notices and announcements</p>
                    </div>
                    <div class="flex gap-2">
                        <Button>
                            <Plus class="w-4 h-4 mr-2" />
                            Add Notice
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <Card class="mb-6">
                <CardHeader>
                    <CardTitle>Search & Filter</CardTitle>
                    <CardDescription>Find notices quickly</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search notices by title or content..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <select v-model="selectedType" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="all">All Types</option>
                                <option value="holiday">Holiday</option>
                                <option value="meeting">Meeting</option>
                                <option value="exam">Exam</option>
                                <option value="policy">Policy</option>
                                <option value="event">Event</option>
                            </select>
                            <select v-model="selectedPriority" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="all">All Priorities</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                            <Button variant="outline">
                                <Filter class="w-4 h-4 mr-2" />
                                Filter
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Notices List -->
            <div class="space-y-4">
                <Card v-for="notice in notices" :key="notice.id">
                    <CardHeader>
                        <div class="flex items-start justify-between">
                            <div>
                                <CardTitle>{{ notice.title }}</CardTitle>
                                <CardDescription>Published {{ new Date(notice.published_at).toLocaleDateString() }}</CardDescription>
                            </div>
                            <div class="flex gap-2">
                                <Badge :class="notice.priority === 'high' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : notice.priority === 'medium' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'">
                                    {{ notice.priority }}
                                </Badge>
                                <Badge variant="outline" class="capitalize">
                                    {{ notice.type }}
                                </Badge>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="prose prose-sm max-w-none text-gray-600 dark:text-gray-400">
                            {{ notice.content }}
                        </div>
                        
                        <div class="mt-4 pt-4 border-t">
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center">
                                    <Calendar class="w-4 h-4 mr-1" />
                                    <span>Expires: {{ new Date(notice.expires_at).toLocaleDateString() }}</span>
                                </div>
                                <div class="flex items-center">
                                    <Tag class="w-4 h-4 mr-1" />
                                    <span>{{ notice.target_audience }}</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <Link :href="route('notice.show', notice.id)">
                                    <Button variant="ghost" size="sm">
                                        <Eye class="w-4 h-4 mr-2" />
                                        Read More
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </Sidebar>
</template>
