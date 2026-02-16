<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
    Search,
    Filter,
    Plus,
    Bus,
    MapPin,
    Users,
    Calendar,
    Phone,
    Eye,
    AlertTriangle
} from 'lucide-vue-next';

defineProps({
    routes: Array,
    stats: Object
});

const searchQuery = ref('');
const selectedStatus = ref('all');
</script>

<template>
    <Head title="Transport Management" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Bus class="w-5 h-5" />
                <span>Transport</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Transport</h2>
                        <p class="text-gray-600 dark:text-gray-400">Manage school transport routes and vehicles</p>
                    </div>
                    <div class="flex gap-2">
                        <Button>
                            <Plus class="w-4 h-4 mr-2" />
                            Add Route
                        </Button>
                        <Button variant="outline">
                            <Filter class="w-4 h-4 mr-2" />
                            Filter
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                <Bus class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Routes</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total_routes }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                                <Users class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Routes</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.active_routes }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                                <Users class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Students</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total_students.toLocaleString() }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mr-4">
                                <Bus class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Vehicles</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total_vehicles }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Search and Filters -->
            <Card class="mb-6">
                <CardHeader>
                    <CardTitle>Search & Filter</CardTitle>
                    <CardDescription>Find transport routes quickly</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search routes by name or area..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <select v-model="selectedStatus" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <Button variant="outline">
                                <Filter class="w-4 h-4 mr-2" />
                                Filter
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Routes List -->
            <Card>
                <CardHeader>
                    <CardTitle>Transport Routes</CardTitle>
                    <CardDescription>All school transport routes</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Route Name</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Vehicle</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Capacity</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Driver</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Students</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Status</th>
                                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="transportRoute in routes" :key="transportRoute.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <MapPin class="w-4 h-4 mr-2 text-gray-400" />
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ transportRoute.name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ transportRoute.description }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <Bus class="w-4 h-4 mr-2 text-gray-400" />
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ transportRoute.vehicle }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">Capacity: {{ transportRoute.capacity }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <Users class="w-4 h-4 mr-2 text-gray-400" />
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ transportRoute.driver }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ transportRoute.phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <Users class="w-4 h-4 mr-2 text-gray-400" />
                                            <div class="font-medium text-gray-900 dark:text-white">{{ transportRoute.students_assigned }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <Badge :class="transportRoute.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : transportRoute.status === 'maintenance' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'">
                                            {{ transportRoute.status }}
                                        </Badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <Link :href="route('transport.show', transportRoute.id)">
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
