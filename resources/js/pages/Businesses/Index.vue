<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import { Building2, Users, Plus, ChevronRight, Crown, Search, Filter, TrendingUp, DollarSign } from 'lucide-vue-next';
import AppMobileLayout from '@/layouts/app/AppMobileLayout.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';

interface Business {
    id: number;
    name: string;
    description: string | null;
    created_by: number;
    creator: {
        name: string;
    };
    members: Array<{
        id: number;
        user: {
            name: string;
        };
    }>;
}

interface Props {
    businesses: Business[];
}

const props = defineProps<Props>();
const page = usePage();
const searchQuery = ref('');
const isLoaded = ref(false);

onMounted(() => {
  setTimeout(() => {
    isLoaded.value = true
  }, 100)
})

const formatGBP = (amount: number) => {
    return new Intl.NumberFormat('en-GB', {
        style: 'currency',
        currency: 'GBP',
    }).format(amount);
};

// Mock data for demonstration - replace with real data
const businessStats = {
    totalRevenue: 45231,
    totalProfit: 12345,
    totalBusinesses: props.businesses.length,
    totalMembers: props.businesses.reduce((acc, business) => acc + business.members.length, 0)
}

const filteredBusinesses = computed(() => {
    if (!searchQuery.value) return props.businesses;
    return props.businesses.filter(business => 
        business.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        business.description?.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});
</script>

<template>
    <Head title="My Businesses" />
    <AppMobileLayout>
        <!-- Header Section -->
        <div class="space-y-6">
            <!-- Stats Overview -->
            <div class="grid grid-cols-2 gap-4">
                <Card class="bg-gradient-to-br from-blue-500 to-purple-600 border-0 shadow-xl text-white">
                    <CardContent class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm">Total Revenue</p>
                                <p class="text-2xl font-bold">{{ formatGBP(businessStats.totalRevenue) }}</p>
                            </div>
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <DollarSign class="h-5 w-5" />
                            </div>
                        </div>
                    </CardContent>
                </Card>
                
                <Card class="bg-gradient-to-br from-green-500 to-emerald-600 border-0 shadow-xl text-white">
                    <CardContent class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm">Total Profit</p>
                                <p class="text-2xl font-bold">{{ formatGBP(businessStats.totalProfit) }}</p>
                            </div>
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <TrendingUp class="h-5 w-5" />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Search and Filter -->
            <Card class="bg-white/80 backdrop-blur-sm border-0 shadow-lg">
                <CardContent class="p-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-1 relative">
                            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <Input
                                v-model="searchQuery"
                                placeholder="Search businesses..."
                                class="pl-10 bg-white/50 backdrop-blur-sm border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <Button variant="outline" size="sm" class="border-gray-200 text-gray-700">
                            <Filter class="h-4 w-4 mr-2" />
                            Filter
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Empty State -->
            <Card v-if="filteredBusinesses.length === 0" class="bg-white/80 backdrop-blur-sm border-0 shadow-xl text-center py-12">
                <CardContent>
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <Building2 class="w-10 h-10 text-white" />
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">No businesses found</h2>
                    <p class="text-gray-600 mb-8">
                        {{ searchQuery ? 'Try adjusting your search terms.' : 'Create your first business to start tracking investments and profits.' }}
                    </p>
                    <Button as-child class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white shadow-lg">
                        <Link :href="route('businesses.create')">
                            <Plus class="w-4 h-4 mr-2" />
                            Create Business
                        </Link>
                    </Button>
                </CardContent>
            </Card>

            <!-- Business List -->
            <div v-else class="space-y-4">
                <Card 
                    v-for="(business, index) in filteredBusinesses" 
                    :key="business.id" 
                    class="bg-white/80 backdrop-blur-sm border-0 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105"
                    :style="{ animationDelay: `${index * 100}ms` }"
                >
                    <Link :href="route('businesses.show', business.id)" class="block">
                        <CardContent class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h3 class="text-xl font-bold text-gray-900 truncate">
                                            {{ business.name }}
                                        </h3>
                                        <Badge 
                                            v-if="business.created_by === page.props.auth.user.id" 
                                            variant="secondary"
                                            class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white"
                                        >
                                            <Crown class="w-3 h-3 mr-1" />
                                            Owner
                                        </Badge>
                                    </div>
                                    <p v-if="business.description" class="text-gray-600 text-sm leading-relaxed line-clamp-2">
                                        {{ business.description }}
                                    </p>
                                </div>
                                <ChevronRight class="w-5 h-5 text-gray-400 ml-3 flex-shrink-0" />
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="flex items-center space-x-3 p-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl">
                                    <div class="p-2 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600">
                                        <Building2 class="w-4 h-4 text-white" />
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Created by</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ business.creator.name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3 p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                                    <div class="p-2 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600">
                                        <Users class="w-4 h-4 text-white" />
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Members</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ business.members.length }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                                <div class="text-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                                    <p class="text-xs text-gray-500 mb-1">Total Revenue</p>
                                    <p class="text-lg font-bold text-green-600">{{ formatGBP(0) }}</p>
                                </div>
                                <div class="text-center p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl">
                                    <p class="text-xs text-gray-500 mb-1">Total Profit</p>
                                    <p class="text-lg font-bold text-purple-600">{{ formatGBP(0) }}</p>
                                </div>
                            </div>
                        </CardContent>
                    </Link>
                </Card>
            </div>

            <!-- Create Business CTA -->
            <Card v-if="filteredBusinesses.length > 0" class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800 border-0 shadow-xl">
                <CardContent class="p-6 text-center text-white">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <Plus class="w-8 h-8" />
                    </div>
                    <h3 class="text-xl font-bold mb-2">Ready to grow?</h3>
                    <p class="text-blue-100 mb-6">Create another business to expand your portfolio</p>
                    <Button as-child variant="secondary" class="bg-white text-blue-600 hover:bg-gray-100">
                        <Link :href="route('businesses.create')">
                            <Plus class="w-4 h-4 mr-2" />
                            Create New Business
                        </Link>
                    </Button>
                </CardContent>
            </Card>
        </div>
    </AppMobileLayout>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom animations */
@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Apply animations to cards */
.card-animate {
  animation: slideInUp 0.6s ease-out forwards;
}

.fade-in {
  animation: fadeIn 0.8s ease-out forwards;
}

/* Smooth transitions */
* {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hover effects */
.hover-lift {
  transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
}

.hover-lift:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: rgba(0, 0, 0, 0.2);
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(0, 0, 0, 0.3);
}

/* Mobile optimizations */
@media (max-width: 768px) {
  .grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
</style> 