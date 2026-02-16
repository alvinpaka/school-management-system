<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { 
    Search,
    Filter,
    MapPin,
    Phone,
    Mail,
    Globe,
    Navigation,
    Building,
    AlertTriangle,
    Users,
    Bus
} from 'lucide-vue-next';

defineProps({
    schoolInfo: Object,
    facilities: Array,
    transportRoutes: Array,
    emergencyServices: Array
});

const searchQuery = ref('');
const selectedCategory = ref('all');
</script>

<template>
    <Head title="School Map" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <MapPin class="w-5 h-5" />
                <span>Map</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">School Map & Directory</h2>
                        <p class="text-gray-600 dark:text-gray-400">Interactive map of school facilities and locations</p>
                    </div>
                    <div class="flex gap-2">
                        <Button>
                            <Search class="w-4 h-4 mr-2" />
                            Search Location
                        </Button>
                        <Button variant="outline">
                            <Filter class="w-4 h-4 mr-2" />
                            Filter
                        </Button>
                    </div>
                </div>
            </div>

            <!-- School Information -->
            <Card class="mb-6">
                <CardHeader>
                    <CardTitle>School Information</CardTitle>
                    <CardDescription>Basic school details and contact information</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Information</h3>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <Building class="w-5 h-5 mr-3 text-gray-400" />
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ schoolInfo.name }}</h4>
                                        <p class="text-gray-600 dark:text-gray-400">{{ schoolInfo.address }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <Phone class="w-4 h-4 mr-2 text-gray-400" />
                                    <span class="text-gray-900 dark:text-white">{{ schoolInfo.phone }}</span>
                                </div>
                                <div class="flex items-center">
                                    <Mail class="w-4 h-4 mr-2 text-gray-400" />
                                    <a :href="`https://maps.google.com/?q=${schoolInfo.website}`" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ schoolInfo.website }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Map</h3>
                            <div class="h-96 bg-gray-200 dark:bg-gray-800 rounded-lg">
                                <!-- Map placeholder -->
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
            <!-- Facilities -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <Card v-for="facility in facilities" :key="facility.id">
                    <CardHeader>
                        <CardTitle>{{ facility.name }}</CardTitle>
                        <CardDescription>{{ facility.description }}</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                <Building class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">{{ facility.name }}</h4>
                                <Badge :class="facility.type === 'academic' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'">
                                    {{ facility.type }}
                                </Badge>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">{{ facility.description }}</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Transport Routes -->
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Transport Routes</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <Card v-for="route in transportRoutes" :key="route.id">
                        <CardHeader>
                            <CardTitle>{{ route.name }}</CardTitle>
                            <CardDescription>Route details and schedule</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <MapPin class="w-4 h-4 mr-2 text-gray-400" />
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">{{ route.name }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ route.description }}</p>
                                        </div>
                                    </div>
                                    <Badge :class="route.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'">
                                        {{ route.status }}
                                    </Badge>
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Schedule</h4>
                                <div class="space-y-2">
                                    <div v-for="stop in route.stops" :key="stop.name" class="flex items-center text-sm">
                                        <span class="font-medium">{{ stop.time }} - {{ stop.name }}</span>
                                        <span class="text-gray-500">({{ stop.students }} students)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end mt-4">
                                <Button variant="outline" size="sm">
                                    View Route Details
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Emergency Services -->
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Emergency Services</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Card v-for="service in emergencyServices" :key="service.name">
                        <CardHeader>
                            <CardTitle>{{ service.name }}</CardTitle>
                            <CardDescription>Emergency contact information</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center mr-4">
                                            <AlertTriangle class="w-6 h-6 text-white" />
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">{{ service.name }}</h4>
                                            <p class="text-gray-600 dark:text-gray-400">{{ service.address }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <Phone class="w-4 h-4 mr-2 text-gray-400" />
                                        <span class="text-gray-900 dark:text-white">{{ service.phone }}</span>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Distance: {{ service.distance }} miles
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </Sidebar>
</template>