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
    <Head title="Library Management | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <BookOpen class="w-5 h-5 text-terracotta" />
                <span class="font-semibold text-warm-text text-dark-text">Library</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 animate-fade-in-up">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter mb-2">Library</h1>
                    <p class="text-warm-muted text-dark-muted font-medium">Manage library books and resources</p>
                </div>
                <div class="flex gap-2">
                    <Button class="accent-terracotta text-white font-black rounded-2xl h-12 px-6 shadow-xl shadow-terracotta/30">
                        <Plus class="w-4 h-4 mr-2" />
                        Add Book
                    </Button>
                    <Button variant="outline" class="border-terracotta/20 text-warm-muted text-dark-muted hover:text-terracotta">
                        <Download class="w-4 h-4 mr-2" />
                        Export
                    </Button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="card-warm p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-terracotta/10 rounded-xl flex items-center justify-center mr-4">
                            <BookOpen class="w-6 h-6 text-terracotta" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-warm-muted text-dark-muted">Total Books</p>
                            <p class="text-2xl font-bold text-warm-text text-dark-text">{{ stats.total_books.toLocaleString() }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-warm p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-forest/10 rounded-xl flex items-center justify-center mr-4">
                            <TrendingUp class="w-6 h-6 text-forest" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-warm-muted text-dark-muted">Available</p>
                            <p class="text-2xl font-bold text-warm-text text-dark-text">{{ stats.available_books.toLocaleString() }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-warm p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-amber/10 rounded-xl flex items-center justify-center mr-4">
                            <Calendar class="w-6 h-6 text-amber" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-warm-muted text-dark-muted">Borrowed</p>
                            <p class="text-2xl font-bold text-warm-text text-dark-text">{{ stats.borrowed_books.toLocaleString() }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-warm p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-destructive/10 rounded-xl flex items-center justify-center mr-4">
                            <Calendar class="w-6 h-6 text-destructive" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-warm-muted text-dark-muted">Overdue</p>
                            <p class="text-2xl font-bold text-warm-text text-dark-text">{{ stats.overdue_books.toLocaleString() }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-warm p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-terracotta/10 rounded-xl flex items-center justify-center mr-4">
                            <Plus class="w-6 h-6 text-terracotta" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-warm-muted text-dark-muted">New Arrivals</p>
                            <p class="text-2xl font-bold text-warm-text text-dark-text">{{ stats.new_arrivals.toLocaleString() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="card-warm">
                <div class="p-6 border-b border-terracotta/20">
                    <h3 class="text-lg font-black text-warm-text text-dark-text">Search & Filter</h3>
                    <p class="text-sm text-warm-muted text-dark-muted mt-1">Find books quickly</p>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-warm-muted text-dark-muted w-4 h-4" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search books by title, author, or ISBN..."
                                    class="w-full pl-10 pr-4 py-2 border border-terracotta/20 rounded-lg focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta bg-white bg-dark-bg text-warm-text text-dark-text"
                                />
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <select v-model="selectedCategory" class="px-4 py-2 border border-terracotta/20 rounded-lg focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta bg-white bg-dark-bg text-warm-text text-dark-text">
                                <option value="all">All Categories</option>
                                <option value="education">Education</option>
                                <option value="science">Science</option>
                                <option value="arts">Arts</option>
                                <option value="literature">Literature</option>
                            </select>
                            <Button variant="outline" class="border-terracotta/20">
                                <Filter class="w-4 h-4 mr-2" />
                                Filter
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Books -->
            <div class="card-warm">
                <div class="p-6 border-b border-terracotta/20">
                    <h3 class="text-lg font-black text-warm-text text-dark-text">Recent Books</h3>
                    <p class="text-sm text-warm-muted text-dark-muted mt-1">Latest additions to the library</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-terracotta/5">
                            <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-warm-muted text-dark-muted border-b border-terracotta/20">
                                <th class="px-6 py-4">Title</th>
                                <th class="px-6 py-4">Author</th>
                                <th class="px-6 py-4">Category</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-terracotta/20">
                            <tr v-for="book in recentBooks" :key="book.id" class="hover:bg-terracotta/5 transition-colors duration-300">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-10 bg-terracotta/10 rounded flex items-center justify-center mr-3">
                                            <BookOpen class="w-4 h-4 text-terracotta" />
                                        </div>
                                        <div>
                                            <div class="font-medium text-warm-text text-dark-text">{{ book.title }}</div>
                                            <div class="text-sm text-warm-muted text-dark-muted">{{ book.author }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-warm-text text-dark-text">{{ book.author }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <Badge :class="book.category === 'Education' ? 'bg-terracotta/10 text-terracotta border-0' : book.category === 'Science' ? 'bg-forest/10 text-forest border-0' : 'bg-amber/10 text-amber border-0'">
                                        {{ book.category }}
                                    </Badge>
                                </td>
                                <td class="px-6 py-4">
                                    <Badge :class="book.status === 'available' ? 'bg-forest/10 text-forest border-0' : 'bg-amber/10 text-amber border-0'">
                                        {{ book.status }}
                                    </Badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <Link :href="route('library.show', book.id)">
                                        <Button variant="ghost" size="sm" class="hover:bg-terracotta/10">
                                            <Eye class="w-4 h-4 text-terracotta" />
                                            View
                                        </Button>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </Sidebar>
</template>