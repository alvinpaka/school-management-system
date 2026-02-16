<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
    Search,
    Filter,
    Download,
    Plus,
    BookOpen,
    Eye,
    Calendar,
    TrendingUp
} from 'lucide-vue-next';

defineProps({
    stats: Object,
    recentBooks: Array
});

const searchQuery = ref('');
const selectedCategory = ref('all');
</script>

<template>
    <Head title="Library Management" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <BookOpen class="w-5 h-5" />
                <span>Library</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Library</h2>
                        <p class="text-gray-600 dark:text-gray-400">Manage library books and resources</p>
                    </div>
                    <div class="flex gap-2">
                        <Button>
                            <Plus class="w-4 h-4 mr-2" />
                            Add Book
                        </Button>
                        <Button variant="outline">
                            <Download class="w-4 h-4 mr-2" />
                            Export
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                <BookOpen class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Books</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total_books.toLocaleString() }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                                <TrendingUp class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Available</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.available_books.toLocaleString() }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mr-4">
                                <Calendar class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Borrowed</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.borrowed_books.toLocaleString() }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center mr-4">
                                <Calendar class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Overdue</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.overdue_books.toLocaleString() }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                                <Plus class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">New Arrivals</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.new_arrivals.toLocaleString() }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Search and Filters -->
            <Card class="mb-6">
                <CardHeader>
                    <CardTitle>Search & Filter</CardTitle>
                    <CardDescription>Find books quickly</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search books by title, author, or ISBN..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <select v-model="selectedCategory" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="all">All Categories</option>
                                <option value="education">Education</option>
                                <option value="science">Science</option>
                                <option value="arts">Arts</option>
                                <option value="literature">Literature</option>
                            </select>
                            <Button variant="outline">
                                <Filter class="w-4 h-4 mr-2" />
                                Filter
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Recent Books -->
            <Card>
                <CardHeader>
                    <CardTitle>Recent Books</CardTitle>
                    <CardDescription>Latest additions to the library</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Title</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Author</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Category</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Status</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="book in recentBooks" :key="book.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-10 bg-gray-200 rounded flex items-center justify-center mr-3">
                                                <BookOpen class="w-4 h-4 text-gray-600" />
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ book.title }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ book.author }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-900 dark:text-white">{{ book.author }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <Badge :class="book.category === 'Education' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : book.category === 'Science' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400'">
                                            {{ book.category }}
                                        </Badge>
                                    </td>
                                    <td class="px-6 py-4">
                                        <Badge :class="book.status === 'available' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'">
                                            {{ book.status }}
                                        </Badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <Link :href="route('library.show', book.id)">
                                            <Button variant="ghost" size="sm">
                                                <Eye class="w-4 h-4" />
                                                View
                                            </Button>
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
