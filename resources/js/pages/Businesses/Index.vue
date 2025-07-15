<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import { Building2, Users, Plus, ChevronRight, Crown, Search, Filter, TrendingUp, DollarSign } from 'lucide-vue-next';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import AppMobileLayout from '@/layouts/app/AppMobileLayout.vue'

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

const formatCurrency = (amount: number) => {
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
  <AppMobileLayout>
    <div class="space-y-4 p-4">
        <Card v-if="filteredBusinesses.length === 0" class="text-center py-12">
            <CardContent>
                <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-muted flex items-center justify-center">
                    <Building2 class="w-10 h-10" />
                </div>
                <h2 class="text-xl font-bold mb-2">No businesses found</h2>
                <p class="text-muted-foreground mb-8">
                    {{ searchQuery ? 'Try adjusting your search terms.' : 'Create your first business to start tracking investments and profits.' }}
                </p>
                <Button as-child>
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
                class="hover:shadow-md transition-all duration-300"
            >
                <Link :href="route('businesses.show', business.id)" class="block">
                    <CardContent class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h3 class="text-xl font-bold truncate">
                                        {{ business.name }}
                                    </h3>
                                    <Badge 
                                        v-if="business.created_by === page.props.auth.user.id" 
                                        variant="secondary"
                                    >
                                        <Crown class="w-3 h-3 mr-1" />
                                        Owner
                                    </Badge>
                                </div>
                                <p v-if="business.description" class="text-muted-foreground text-sm leading-relaxed line-clamp-2">
                                    {{ business.description }}
                                </p>
                            </div>
                            <ChevronRight class="w-5 h-5 text-muted-foreground ml-3 flex-shrink-0" />
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="flex items-center space-x-3 p-3 bg-muted rounded-xl">
                                <div class="p-2 rounded-lg bg-primary">
                                    <Building2 class="w-4 h-4 text-primary-foreground" />
                                </div>
                                <div>
                                    <p class="text-xs text-muted-foreground">Created by</p>
                                    <p class="text-sm font-semibold">{{ business.creator.name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-muted rounded-xl">
                                <div class="p-2 rounded-lg bg-primary">
                                    <Users class="w-4 h-4 text-primary-foreground" />
                                </div>
                                <div>
                                    <p class="text-xs text-muted-foreground">Members</p>
                                    <p class="text-sm font-semibold">{{ business.members.length }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <Separator class="mb-4" />
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-muted rounded-xl">
                                <p class="text-xs text-muted-foreground mb-1">Total Revenue</p>
                                <p class="text-lg font-bold text-green-600">{{ formatCurrency(0) }}</p>
                            </div>
                            <div class="text-center p-3 bg-muted rounded-xl">
                                <p class="text-xs text-muted-foreground mb-1">Total Profit</p>
                                <p class="text-lg font-bold text-blue-600">{{ formatCurrency(0) }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Link>
            </Card>
        </div>

        <!-- Create Business CTA -->
        <Card v-if="filteredBusinesses.length > 0" class="bg-primary text-primary-foreground">
            <CardContent class="p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-primary-foreground/20 flex items-center justify-center">
                    <Plus class="w-8 h-8" />
                </div>
                <h3 class="text-xl font-bold mb-2">Ready to grow?</h3>
                <p class="text-primary-foreground/80 mb-6">Create another business to expand your portfolio</p>
                <Button as-child variant="secondary" class="bg-primary-foreground text-primary hover:bg-primary-foreground/90">
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