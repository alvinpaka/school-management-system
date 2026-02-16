<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ArrowLeft, Bus, MapPin, Users, Phone, Calendar, AlertTriangle } from 'lucide-vue-next';

defineProps({
    route: Object
});
</script>

<template>
    <Head title="Transport Route Details" />

    <Sidebar>
        <template #header-title>
            Transport Route Details
        </template>

        <div class="mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex items-center">
                    <Link :href="route('transport.index')" class="flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Back to Transport
                    </Link>
                </div>
            </div>

            <!-- Route Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <Card class="lg:col-span-2">
                    <CardHeader>
                        <CardTitle>Route Information</CardTitle>
                        <CardDescription>Basic route details and schedule</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-6">
                            <!-- Route Info -->
                            <div class="flex items-center space-x-4 pb-6 border-b">
                                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center">
                                    <Bus class="w-8 h-8 text-white" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ route.name }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400">{{ route.description }}</p>
                                </div>
                            </div>

                            <!-- Schedule -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Schedule</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <Calendar class="w-4 h-4 mr-2 text-gray-400" />
                                            <span class="text-gray-900 dark:text-white">Departure Time</span>
                                        </div>
                                        <p class="text-gray-900 dark:text-white font-medium">{{ route.departure_time }}</p>
                                        
                                        <div class="flex items-center">
                                            <Calendar class="w-4 h-4 mr-2 text-gray-400" />
                                            <span class="text-gray-900 dark:text-white">Arrival Time</span>
                                        </div>
                                        <p class="text-gray-900 dark:text-white font-medium">{{ route.arrival_time }}</p>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Vehicle Details</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <Bus class="w-4 h-4 mr-2 text-gray-400" />
                                            <span class="text-gray-900 dark:text-white">Vehicle Type</span>
                                        </div>
                                        <p class="text-gray-900 dark:text-white font-medium">{{ route.vehicle_type }}</p>
                                        
                                        <div class="flex items-center">
                                            <span class="text-gray-900 dark:text-white">Vehicle Number</span>
                                        </div>
                                        <p class="text-gray-900 dark:text-white font-medium">{{ route.vehicle_number || 'N/A' }}</p>
                                        
                                        <div class="flex items-center">
                                            <span class="text-gray-900 dark:text-white">Capacity</span>
                                        </div>
                                        <p class="text-gray-900 dark:text-white font-medium">{{ route.capacity }} students</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Driver Information -->
                <Card>
                    <CardHeader>
                        <CardTitle>Driver Information</CardTitle>
                        <CardDescription>Driver details and contact</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-6">
                            <div class="flex items-center space-x-4 pb-6 border-b">
                                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center">
                                    <Users class="w-8 h-8 text-white" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ route.driver.name }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400">{{ route.driver.license }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Details</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <Phone class="w-4 h-4 mr-2 text-gray-400" />
                                            <span class="text-gray-900 dark:text-white">Phone</span>
                                        </div>
                                        <p class="text-gray-900 dark:text-white font-medium">{{ route.driver.phone }}</p>
                                        
                                        <div class="flex items-center">
                                            <span class="text-gray-900 dark:text-white">Experience</span>
                                        </div>
                                        <p class="text-gray-900 dark:text-white font-medium">{{ route.driver.experience }} years</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Route Status -->
                <Card>
                    <CardHeader>
                        <CardTitle>Route Status</CardTitle>
                        <CardDescription>Current route status and alerts</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <Badge :class="route.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'">
                                        {{ route.status }}
                                    </Badge>
                                </div>
                                <div v-if="route.status === 'maintenance'" class="flex items-center text-yellow-600">
                                    <AlertTriangle class="w-4 h-4 mr-2" />
                                    <span class="text-sm font-medium">Under Maintenance</span>
                                </div>
                            </div>
                            
                            <div class="text-gray-600 dark:text-gray-400">
                                <p v-if="route.status === 'active'">Route is currently operating normally.</p>
                                <p v-if="route.status === 'maintenance'">Route is temporarily under maintenance. Alternative arrangements have been made.</p>
                                <p v-if="route.status === 'inactive'">Route is currently inactive.</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Students Assigned -->
                <Card>
                    <CardHeader>
                        <CardTitle>Assigned Students ({{ route.students_assigned }})</CardTitle>
                        <CardDescription>Students using this transport route</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-3">
                            <div v-for="student in route.students_assigned" :key="student.name" class="flex items-center justify-between p-3 border rounded-lg">
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ student.name }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ student.class }} • {{ student.pickup_point }}</p>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ student.pickup_time }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </Sidebar>
</template>
