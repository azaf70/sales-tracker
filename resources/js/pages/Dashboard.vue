<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Separator } from '@/components/ui/separator'
import AppMobileLayout from '@/layouts/app/AppMobileLayout.vue'
import { 
  TrendingUp, 
  TrendingDown, 
  Building2, 
  Users, 
  DollarSign, 
  Target, 
  ArrowUpRight, 
  ArrowDownRight,
  Plus,
  Activity,
  BarChart3,
  Calendar,
  Clock
} from 'lucide-vue-next'

const isLoaded = ref(false)

onMounted(() => {
  setTimeout(() => {
    isLoaded.value = true
  }, 100)
})

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('en-GB', {
    style: 'currency',
    currency: 'GBP',
  }).format(amount)
}

// Mock data - replace with real data from your backend
const stats = [
  {
    title: 'Total Revenue',
    value: '£45,231',
    change: '+20.1%',
    trend: 'up',
    icon: DollarSign,
  },
  {
    title: 'Profit Margin',
    value: '23.5%',
    change: '+2.1%',
    trend: 'up',
    icon: Target,
  }
]

const recentActivities = [
  {
    id: 1,
    type: 'sale',
    title: 'New sale recorded',
    description: '£2,500 sale in Tech Solutions Ltd',
    time: '2 minutes ago',
    icon: TrendingUp,
  },
  {
    id: 2,
    type: 'investment',
    title: 'Investment added',
    description: '£10,000 investment in Retail Ventures',
    time: '1 hour ago',
    icon: DollarSign,
  },
  {
    id: 3,
    type: 'member',
    title: 'New team member',
    description: 'Sarah Johnson joined Marketing Pro',
    time: '3 hours ago',
    icon: Users,
  },
  {
    id: 4,
    type: 'business',
    title: 'Business created',
    description: 'New business "Digital Creations" added',
    time: '5 hours ago',
    icon: Building2,
  }
]

const quickActions = [
  {
    title: 'Record Sale',
    description: 'Add a new sale transaction',
    href: '/sales/create',
    icon: TrendingUp,
  },
  {
    title: 'Add Investment',
    description: 'Track a new investment',
    href: '/investments/create',
    icon: DollarSign,
  },
]
</script>

<template>
  <AppMobileLayout>
    <div class="space-y-4 p-4">
      <!-- Welcome Section -->
      <Card>
        <CardContent class="p-4">
          <div class="text-center">
            <div class="w-16 h-16 bg-muted rounded-full flex items-center justify-center mx-auto mb-2">
              <Activity class="h-3 w-8" />
            </div>
            <h1 class="text-xl font-bold mb-2">Welcome back! 👋</h1>
            <p class="text-muted-foreground text-sm leading-relaxed">
              Your business empire is growing. Here's what's happening today.
            </p>
          </div>
        </CardContent>
      </Card>

      <!-- Stats Grid -->
      <div class="grid grid-cols-2 gap-3">
        <Card 
          v-for="(stat, index) in stats" 
          :key="stat.title"
        >
          <CardHeader>
            <div class="flex items-center justify-between">
              <CardTitle class="text-sm font-medium text-muted-foreground">{{ stat.title }}</CardTitle>
              <div class="p-2 rounded-lg bg-muted">
                <component :is="stat.icon" class="h-5 w-5" />
              </div>
            </div>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold mb-2">{{ stat.value }}</div>
            <div class="flex items-center text-sm" :class="stat.trend === 'up' ? 'text-green-600' : 'text-red-600'">
              <component :is="stat.trend === 'up' ? ArrowUpRight : ArrowDownRight" class="h-4 w-4 mr-1" />
              <span>{{ stat.change }}</span>
            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Quick Actions -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center gap-4 text-xl font-bold">
            <div class="p-2 bg-primary rounded-lg">
              <Plus class="h-5 w-5 text-primary-foreground" />
            </div>
            Quick Actions
          </CardTitle>
          <CardDescription>Get things done faster with these shortcuts</CardDescription>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 gap-4">
            <Link
              v-for="action in quickActions"
              :key="action.title"
              :href="action.href"
              class="group block"
            >
              <div class="p-4 rounded-xl border bg-card hover:bg-accent transition-colors">
                <div class="flex items-center space-x-3">
                  <div class="p-2 rounded-lg bg-primary">
                    <component :is="action.icon" class="h-5 w-5 text-primary-foreground" />
                  </div>
                  <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-sm">{{ action.title }}</h3>
                    <p class="text-xs text-muted-foreground mt-1">{{ action.description }}</p>
                  </div>
                </div>
              </div>
            </Link>
          </div>
        </CardContent>
      </Card>

      <!-- Recent Activity -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center gap-4 text-xl font-bold">
            <div class="p-2 bg-primary rounded-lg">
              <Activity class="h-5 w-5 text-primary-foreground" />
            </div>
            Recent Activity
          </CardTitle>
          <CardDescription>Latest updates from your businesses</CardDescription>
        </CardHeader>
        <CardContent>
          <div class="space-y-4">
            <div
              v-for="(activity, index) in recentActivities"
              :key="activity.id"
              class="flex items-start space-x-4 rounded-xl hover:bg-accent transition-colors"
            >
              <div class="p-2 rounded-lg bg-muted">
                <component :is="activity.icon" class="h-4 w-4" />
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="font-medium text-sm">{{ activity.title }}</h4>
                <p class="text-sm text-muted-foreground mt-1">{{ activity.description }}</p>
                <div class="flex items-center mt-2 text-xs text-muted-foreground">
                  <Clock class="h-3 w-3 mr-1" />
                  {{ activity.time }}
                </div>
              </div>
              <Badge 
                :variant="activity.type === 'sale' ? 'default' : 'secondary'"
                class="text-xs"
              >
                {{ activity.type }}
              </Badge>
            </div>
          </div>
          
          <Separator class="my-6" />
          
          <Button variant="outline" class="w-full">
            View All Activity
          </Button>
        </CardContent>
      </Card>

      <!-- Performance Overview -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center gap-3 text-lg font-bold">
            <div class="p-2 bg-primary rounded-lg">
              <BarChart3 class="h-5 w-5 text-primary-foreground" />
            </div>
            Performance Overview
          </CardTitle>
          <CardDescription>Your business performance this month</CardDescription>
        </CardHeader>
        <CardContent>
          <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-muted rounded-lg">
              <div>
                <h4 class="font-semibold text-sm">Revenue Growth</h4>
                <p class="text-xs text-muted-foreground">This month vs last month</p>
              </div>
              <div class="text-right">
                <div class="text-lg font-bold text-green-600">+23.5%</div>
                <div class="text-xs text-green-600 flex items-center">
                  <ArrowUpRight class="h-3 w-3 mr-1" />
                  £12,450
                </div>
              </div>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-muted rounded-lg">
              <div>
                <h4 class="font-semibold text-sm">New Businesses</h4>
                <p class="text-xs text-muted-foreground">Added this month</p>
              </div>
              <div class="text-right">
                <div class="text-lg font-bold text-blue-600">+3</div>
                <div class="text-xs text-blue-600">Total: 15</div>
              </div>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-muted rounded-lg">
              <div>
                <h4 class="font-semibold text-sm">Team Growth</h4>
                <p class="text-xs text-muted-foreground">New members this month</p>
              </div>
              <div class="text-right">
                <div class="text-lg font-bold text-orange-600">+8</div>
                <div class="text-xs text-orange-600">Total: 48</div>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppMobileLayout>
</template>
