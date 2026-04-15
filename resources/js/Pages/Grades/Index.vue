<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
    Trophy,
    TrendingUp,
    Users,
    BookOpen,
    Calendar,
    CheckCircle
} from 'lucide-vue-next';

defineProps({
    grades: Array,
    stats: Object
});
</script>

<template>
    <Head title="Grades Management | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Trophy class="w-5 h-5 text-terracotta" />
                <span class="font-semibold text-warm-text text-dark-text">Grades</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 animate-fade-in-up">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter mb-2">Grades Overview</h1>
                    <p class="text-warm-muted text-dark-muted font-medium">View and manage student grades</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="card-warm p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-forest/10 rounded-xl flex items-center justify-center mr-4">
                            <Trophy class="w-6 h-6 text-forest" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-warm-muted text-dark-muted">Average Grade</p>
                            <p class="text-2xl font-bold text-warm-text text-dark-text">{{ stats?.average_grade || 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-warm p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-terracotta/10 rounded-xl flex items-center justify-center mr-4">
                            <TrendingUp class="w-6 h-6 text-terracotta" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-warm-muted text-dark-muted">Pass Rate</p>
                            <p class="text-2xl font-bold text-warm-text text-dark-text">{{ stats?.pass_rate || 'N/A' }}%</p>
                        </div>
                    </div>
                </div>

                <div class="card-warm p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-amber/10 rounded-xl flex items-center justify-center mr-4">
                            <Users class="w-6 h-6 text-amber" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-warm-muted text-dark-muted">Total Students</p>
                            <p class="text-2xl font-bold text-warm-text text-dark-text">{{ stats?.total_students || 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-warm p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-terracotta/10 rounded-xl flex items-center justify-center mr-4">
                            <BookOpen class="w-6 h-6 text-terracotta" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-warm-muted text-dark-muted">Total Exams</p>
                            <p class="text-2xl font-bold text-warm-text text-dark-text">{{ stats?.total_exams || 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Grades -->
            <div class="card-warm">
                <div class="p-6 border-b border-terracotta/20">
                    <h3 class="text-lg font-black text-warm-text text-dark-text">Recent Grades</h3>
                    <p class="text-sm text-warm-muted text-dark-muted mt-1">Latest grade entries across all classes</p>
                </div>
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-terracotta/5 rounded-3xl flex items-center justify-center mx-auto mb-4">
                        <Trophy class="w-10 h-10 text-terracotta/30" />
                    </div>
                    <h3 class="text-xl font-black text-warm-text text-dark-text mb-2 tracking-tighter">Grades Module</h3>
                    <p class="text-warm-muted text-dark-muted mb-6">Comprehensive grade management system</p>
                    <div class="space-y-3 text-left max-w-md mx-auto">
                        <div class="flex items-center text-sm text-warm-muted text-dark-muted">
                            <CheckCircle class="w-4 h-4 mr-3 text-forest" />
                            Grade entry and management
                        </div>
                        <div class="flex items-center text-sm text-warm-muted text-dark-muted">
                            <CheckCircle class="w-4 h-4 mr-3 text-forest" />
                            Performance analytics
                        </div>
                        <div class="flex items-center text-sm text-warm-muted text-dark-muted">
                            <CheckCircle class="w-4 h-4 mr-3 text-forest" />
                            Grade reports and transcripts
                        </div>
                        <div class="flex items-center text-sm text-warm-muted text-dark-muted">
                            <CheckCircle class="w-4 h-4 mr-3 text-forest" />
                            Class performance tracking
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Sidebar>
</template>