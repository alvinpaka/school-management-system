<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import Pagination from '@/components/ui/pagination.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { 
    Plus,
    Edit,
    Trash2,
    Search,
    Filter,
    Download,
    Eye,
    Phone,
    Mail,
    Users
} from 'lucide-vue-next';

const { parents, filters } = defineProps({
    parents: Object,
    filters: Object
});

const searchQuery = ref(filters?.search || '');

const deleteParent = (id) => {
    if (confirm('Are you sure you want to delete this parent?')) {
        router.delete(route('parents.destroy', id));
    }
};

const handlePageChange = (page) => {
    const params = { page };
    if (searchQuery.value) {
        params.search = searchQuery.value;
    }
    router.get(route('parents.index'), params, { preserveState: true });
};

// Watch for search query changes
watch(searchQuery, (newValue) => {
    router.get(route('parents.index'), { 
        search: newValue, 
        page: 1 
    }, { preserveState: true });
}, { debounce: 300 });
</script>

<template>
    <Head title="Parents Management" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Users class="w-5 h-5" />
                <span>Parents</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Parents</h2>
                        <p class="text-gray-600 dark:text-gray-400">Manage all parents in the system</p>
                    </div>
                    <div class="flex gap-2">
                        <Link :href="route('parents.create')">
                            <Button>
                                <Plus class="w-4 h-4 mr-2" />
                                Add Parent
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Parents List -->
            <Card class="mb-6">
                <CardHeader>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <CardTitle>All Parents</CardTitle>
                            <CardDescription>{{ parents.total }} registered parents</CardDescription>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                                <input
                                    type="text"
                                    v-model="searchQuery"
                                    placeholder="Search parents..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                />
                            </div>
                            <Button variant="outline" size="sm">
                                <Filter class="w-4 h-4" />
                            </Button>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Name</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Phone</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Address</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Occupation</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Relationship</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="parent in parents.data" :key="parent.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 mr-3">
                                                <img 
                                                    v-if="parent.user?.photo" 
                                                    :src="`/storage/${parent.user.photo}`" 
                                                    :alt="parent.user?.name"
                                                    class="w-full h-full object-cover"
                                                />
                                                <div v-else class="flex items-center justify-center h-full">
                                                    <span class="text-blue-500 font-bold text-xs">{{ parent.user?.name?.charAt(0) || 'P' }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ parent.user?.name || 'Unknown' }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ parent.user?.email || 'No email' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <Phone class="w-4 h-4 mr-2 text-gray-400" />
                                            <span class="text-gray-900 dark:text-white">{{ parent.phone || 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-900 dark:text-white">{{ parent.address || 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-900 dark:text-white">{{ parent.occupation || 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <Badge :class="parent.relationship_to_student === 'Father' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-400'">
                                            {{ parent.relationship_to_student }}
                                        </Badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <Button variant="ghost" size="sm">
                                                    <Eye class="w-4 h-4" />
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent align="end">
                                                <DropdownMenuItem as-child>
                                                    <Link :href="route('parents.show', parent.id)" class="flex items-center">
                                                        <Eye class="w-4 h-4 mr-2" />
                                                        View Details
                                                    </Link>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem as-child>
                                                    <Link :href="route('parents.edit', parent.id)" class="flex items-center">
                                                        <Edit class="w-4 h-4 mr-2" />
                                                        Edit
                                                    </Link>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem @click="deleteParent(parent.id)" class="flex items-center text-red-600">
                                                    <Trash2 class="w-4 h-4 mr-2" />
                                                    Delete
                                                </DropdownMenuItem>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <Pagination 
                        :data="parents" 
                        @page-change="handlePageChange"
                    />
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
