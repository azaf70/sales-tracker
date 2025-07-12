<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import AppMobileLayout from '@/layouts/app/AppMobileLayout.vue'
import { ArrowLeft, Building2, Loader2, Sparkles, Target, Users, TrendingUp } from 'lucide-vue-next'

const form = useForm({
  name: '',
  description: '',
})

const isLoaded = ref(false)

onMounted(() => {
  setTimeout(() => {
    isLoaded.value = true
  }, 100)
})

const submit = () => {
  form.post(route('businesses.store'))
}
</script>

<template>
  <AppMobileLayout>
    <!-- Hero Header -->
    <div class="relative overflow-hidden">
      <!-- Background Gradient -->
      <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800"></div>
      
      <!-- Animated Background Elements -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 right-10 w-32 h-32 bg-white rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 left-10 w-24 h-24 bg-cyan-400 rounded-full blur-2xl animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/2 w-40 h-40 bg-purple-400 rounded-full blur-3xl animate-pulse delay-500"></div>
      </div>

      <!-- Content -->
      <div class="relative z-10 px-6 py-8">
        <div class="flex items-center gap-4 mb-6">
          <Link :href="route('businesses.index')" as="button">
            <Button variant="ghost" size="sm" class="bg-white/20 backdrop-blur-sm border-white/30 text-white hover:bg-white/30">
              <ArrowLeft class="h-4 w-4" />
            </Button>
          </Link>
          <div class="flex-1">
            <h1 class="text-3xl font-bold text-white mb-2 tracking-tight">Create Business</h1>
            <p class="text-blue-100 text-lg leading-relaxed">Start tracking your family investment journey</p>
          </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-3 gap-4">
          <div class="text-center">
            <div class="text-2xl font-bold text-white">0</div>
            <div class="text-blue-100 text-sm">Businesses</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-white">£0</div>
            <div class="text-blue-100 text-sm">Revenue</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-white">0%</div>
            <div class="text-blue-100 text-sm">ROI</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Form Section -->
    <div class="px-6 -mt-4 relative z-20 space-y-6">
      <!-- Business Details Card -->
      <Card class="bg-white/80 backdrop-blur-sm border-0 shadow-xl">
        <CardHeader>
          <CardTitle class="flex items-center gap-3 text-xl font-bold text-gray-900">
            <div class="p-2 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg">
              <Building2 class="h-5 w-5 text-white" />
            </div>
            Business Details
          </CardTitle>
          <CardDescription class="text-gray-600">
            Enter the basic information about your family business
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-3">
              <Label for="name" class="text-sm font-medium text-gray-700">Business Name *</Label>
              <Input
                id="name"
                v-model="form.name"
                type="text"
                placeholder="Enter business name"
                class="bg-white/50 backdrop-blur-sm border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': form.errors.name }"
                required
              />
              <p v-if="form.errors.name" class="text-sm text-red-600 flex items-center">
                <span class="w-1 h-1 bg-red-500 rounded-full mr-2"></span>
                {{ form.errors.name }}
              </p>
            </div>

            <div class="space-y-3">
              <Label for="description" class="text-sm font-medium text-gray-700">Description (Optional)</Label>
              <Textarea
                id="description"
                v-model="form.description"
                placeholder="Describe your business, goals, and vision..."
                class="bg-white/50 backdrop-blur-sm border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                :class="form.errors.description ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''"
                :rows="4"
              />
              <p v-if="form.errors.description" class="text-sm text-red-600 flex items-center">
                <span class="w-1 h-1 bg-red-500 rounded-full mr-2"></span>
                {{ form.errors.description }}
              </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-4">
              <Link :href="route('businesses.index')" as="button" class="flex-1">
                <Button variant="outline" class="w-full border-gray-200 text-gray-700 hover:bg-gray-50">
                  Cancel
                </Button>
              </Link>
              <Button 
                type="submit" 
                :disabled="form.processing" 
                class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white shadow-lg"
              >
                <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
                <Sparkles v-else class="h-4 w-4 mr-2" />
                {{ form.processing ? 'Creating...' : 'Create Business' }}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>

      <!-- Benefits Card -->
      <Card class="bg-gradient-to-br from-green-500 to-emerald-600 border-0 shadow-xl text-white">
        <CardHeader>
          <CardTitle class="flex items-center gap-3 text-xl font-bold">
            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
              <Target class="h-5 w-5" />
            </div>
            Why Create a Business?
          </CardTitle>
          <CardDescription class="text-green-100">
            Track your family's financial growth and success
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div class="space-y-4">
            <div class="flex items-start space-x-3">
              <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm flex-shrink-0">
                <TrendingUp class="h-4 w-4" />
              </div>
              <div>
                <h4 class="font-semibold text-sm">Track Performance</h4>
                <p class="text-green-100 text-sm">Monitor revenue, profits, and growth over time</p>
              </div>
            </div>
            
            <div class="flex items-start space-x-3">
              <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm flex-shrink-0">
                <Users class="h-4 w-4" />
              </div>
              <div>
                <h4 class="font-semibold text-sm">Team Management</h4>
                <p class="text-green-100 text-sm">Manage family members and ownership percentages</p>
              </div>
            </div>
            
            <div class="flex items-start space-x-3">
              <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm flex-shrink-0">
                <Building2 class="h-4 w-4" />
              </div>
              <div>
                <h4 class="font-semibold text-sm">Investment Tracking</h4>
                <p class="text-green-100 text-sm">Record and monitor all investments and returns</p>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Tips Card -->
      <Card class="bg-white/80 backdrop-blur-sm border-0 shadow-xl">
        <CardHeader>
          <CardTitle class="flex items-center gap-3 text-xl font-bold text-gray-900">
            <div class="p-2 bg-gradient-to-br from-orange-400 to-red-500 rounded-lg">
              <Sparkles class="h-5 w-5 text-white" />
            </div>
            Pro Tips
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-3">
            <div class="flex items-start space-x-3 p-3 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl">
              <div class="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
              <p class="text-sm text-gray-700">Choose a clear, memorable name that reflects your business vision</p>
            </div>
            
            <div class="flex items-start space-x-3 p-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl">
              <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
              <p class="text-sm text-gray-700">Add a detailed description to help team members understand the business goals</p>
            </div>
            
            <div class="flex items-start space-x-3 p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
              <div class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
              <p class="text-sm text-gray-700">You can always invite family members and add investments after creation</p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppMobileLayout>
</template>

<style scoped>
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