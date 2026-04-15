<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select';
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
    <Head title="School Map | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <MapPin class="w-5 h-5 text-terracotta" />
                <span class="font-semibold text-warm-text text-dark-text">Map</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 animate-fade-in-up">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter mb-2">School Map & Directory</h1>
                    <p class="text-warm-muted text-dark-muted font-medium">Interactive map of school facilities and locations</p>
                </div>
                <div class="flex gap-2">
                    <Button class="accent-terracotta text-white">
                        <Search class="w-4 h-4 mr-2" />
                        Search Location
                    </Button>
                    <Button variant="outline" class="border-terracotta/20 text-warm-muted text-dark-muted hover:text-terracotta">
                        <Filter class="w-4 h-4 mr-2" />
                        Filter
                    </Button>
                </div>
            </div>

            <!-- School Information -->
            <div class="card-warm">
                <div class="p-6 border-b border-terracotta/20">
                    <h3 class="text-lg font-black text-warm-text text-dark-text">School Information</h3>
                    <p class="text-sm text-warm-muted text-dark-muted mt-1">Basic school details and contact information</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="text-base font-black text-warm-text text-dark-text mb-4">Contact Information</h4>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <Building class="w-5 h-5 mr-3 text-terracotta mt-0.5" />
                                    <div>
                                        <h4 class="font-medium text-warm-text text-dark-text">{{ schoolInfo.name }}</h4>
                                        <p class="text-warm-muted text-dark-muted">{{ schoolInfo.address }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <Phone class="w-4 h-4 mr-2 text-terracotta" />
                                    <span class="text-warm-text text-dark-text">{{ schoolInfo.phone }}</span>
                                </div>
                                <div class="flex items-center">
                                    <Mail class="w-4 h-4 mr-2 text-terracotta" />
                                    <a :href="`https://maps.google.com/?q=${schoolInfo.website}`" target="_blank" class="text-terracotta hover:text-terracotta/80">
                                        {{ schoolInfo.website }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-base font-black text-warm-text text-dark-text mb-4">Map</h4>
                            <div class="h-96 bg-terracotta/5 border border-terracotta/20 rounded-xl flex items-center justify-center">
                                <div class="text-center">
                                    <MapPin class="w-12 h-12 text-terracotta/30 mx-auto mb-2" />
                                    <p class="text-warm-muted text-dark-muted">Interactive Map Preview</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facilities -->
            <div>
                <h2 class="text-xl font-black text-warm-text text-dark-text mb-4">Facilities</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="facility in facilities" :key="facility.id" class="card-warm">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-terracotta/10 rounded-xl flex items-center justify-center mr-4">
                                    <Building class="w-6 h-6 text-terracotta" />
                                </div>
                                <div>
                                    <h4 class="font-black text-warm-text text-dark-text">{{ facility.name }}</h4>
                                    <Badge :class="facility.type === 'academic' ? 'bg-terracotta/10 text-terracotta border-0' : 'bg-forest/10 text-forest border-0'">
                                        {{ facility.type }}
                                    </Badge>
                                </div>
                            </div>
                            <p class="text-warm-muted text-dark-muted">{{ facility.description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transport Routes -->
            <div>
                <h2 class="text-xl font-black text-warm-text text-dark-text mb-4">Transport Routes</h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div v-for="route in transportRoutes" :key="route.id" class="card-warm">
                        <div class="p-6 border-b border-terracotta/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-black text-warm-text text-dark-text">{{ route.name }}</h3>
                                    <p class="text-sm text-warm-muted text-dark-muted mt-1">Route details and schedule</p>
                                </div>
                                <Badge :class="route.status === 'active' ? 'bg-forest/10 text-forest border-0' : 'bg-amber/10 text-amber border-0'">
                                    {{ route.status }}
                                </Badge>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center">
                                <Bus class="w-4 h-4 mr-2 text-terracotta" />
                                <p class="text-sm text-warm-muted text-dark-muted">{{ route.description }}</p>
                            </div>

                            <div class="border-t border-terracotta/20 pt-4">
                                <h4 class="font-black text-warm-text text-dark-text mb-3">Schedule</h4>
                                <div class="space-y-2">
                                    <div v-for="stop in route.stops" :key="stop.name" class="flex items-center justify-between text-sm">
                                        <div class="flex items-center">
                                            <MapPin class="w-3 h-3 mr-2 text-terracotta" />
                                            <span class="font-medium text-warm-text text-dark-text">{{ stop.time }}</span>
                                            <span class="text-warm-muted text-dark-muted ml-2">{{ stop.name }}</span>
                                        </div>
                                        <span class="text-xs text-warm-muted text-dark-muted">{{ stop.students }} students</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-2">
                                <Button variant="outline" size="sm" class="border-terracotta/20 text-terracotta hover:bg-terracotta/10">
                                    View Route Details
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Services -->
            <div>
                <h2 class="text-xl font-black text-warm-text text-dark-text mb-4">Emergency Services</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="service in emergencyServices" :key="service.name" class="card-warm">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-destructive/10 rounded-xl flex items-center justify-center mr-4">
                                        <AlertTriangle class="w-6 h-6 text-destructive" />
                                    </div>
                                    <div>
                                        <h4 class="font-black text-warm-text text-dark-text">{{ service.name }}</h4>
                                        <p class="text-sm text-warm-muted text-dark-muted">{{ service.address }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <Phone class="w-4 h-4 mr-1 text-terracotta" />
                                    <span class="text-sm font-medium text-warm-text text-dark-text">{{ service.phone }}</span>
                                </div>
                            </div>
                            <div class="text-sm text-warm-muted text-dark-muted">
                                Distance: {{ service.distance }} km
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Sidebar>
</template>