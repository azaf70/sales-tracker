<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import { Card, CardContent, CardDescription, CardHeader, CardTitle, CardFooter } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Separator } from '@/components/ui/separator'
import { Breadcrumb, BreadcrumbItem, BreadcrumbLink, BreadcrumbList, BreadcrumbSeparator } from '@/components/ui/breadcrumb'
import { Slash, ArrowLeft, Building2, Loader2, Sparkles } from 'lucide-vue-next'
import AppMobileLayout from '@/layouts/app/AppMobileLayout.vue'

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

const handleCancel = () => {
  if (window.history.length > 1) {
    window.history.back();
  } else {
    window.location.href = route('businesses.index');
  }
}
</script>

<template>
  <AppMobileLayout>
    <div class="space-y-6 p-4">
      <Breadcrumb>
        <BreadcrumbList>
          <BreadcrumbItem>
            <BreadcrumbLink href="/">Home</BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator><Slash /></BreadcrumbSeparator>
          <BreadcrumbItem>
            <BreadcrumbLink href="/businesses">Businesses</BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator><Slash /></BreadcrumbSeparator>
          <BreadcrumbItem>
            <BreadcrumbLink href="#">Create</BreadcrumbLink>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center gap-2">
            <Building2 class="h-5 w-5" />
            Create Business
          </CardTitle>
          <CardDescription>Start tracking your family investment journey</CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-4">
            <div class="grid gap-2">
              <Label for="name">Business Name</Label>
              <Input
                id="name"
                v-model="form.name"
                type="text"
                placeholder="Enter business name"
                required
                :class="{ 'border-destructive': form.errors.name }"
              />
              <p v-if="form.errors.name" class="text-sm text-destructive">
                {{ form.errors.name }}
              </p>
            </div>
            <div class="grid gap-2">
              <Label for="description">Description (Optional)</Label>
              <Textarea
                id="description"
                v-model="form.description"
                placeholder="Describe your business, goals, and vision..."
                :rows="4"
                :class="form.errors.description ? 'border-destructive' : ''"
              />
              <p v-if="form.errors.description" class="text-sm text-destructive">
                {{ form.errors.description }}
              </p>
            </div>
          </form>
        </CardContent>
        <CardFooter class="flex items-center gap-4 justify-end">
          <Button variant="outline" @click="handleCancel">
            Cancel
          </Button>
          <Button 
            type="submit" 
            :disabled="form.processing"
            @click="submit"
          >
            <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
            <Sparkles v-else class="h-4 w-4 mr-2" />
            {{ form.processing ? 'Creating...' : 'Create Business' }}
          </Button>
        </CardFooter>
      </Card>

      <Separator />

      <!-- Benefits Card -->
      <Card>
        <CardHeader class="mb-2">
          <CardTitle class="flex items-center gap-2">
            <Target class="h-5 w-5" />
            Why Create a Business?
          </CardTitle>
          <CardDescription>
            Track your family's financial growth and success with comprehensive investment management
          </CardDescription>
        </CardHeader>
        <CardContent class="space-y-4">
          <div class="flex items-start space-x-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10">
              <Package class="h-4 w-4 text-primary" />
            </div>
            <div>
              <h4 class="font-semibold text-sm">Product Batch Management</h4>
              <p class="text-sm text-muted-foreground">Create and track product batches with detailed cost and quantity management</p>
            </div>
          </div>
          
          <div class="flex items-start space-x-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10">
              <DollarSign class="h-4 w-4 text-primary" />
            </div>
            <div>
              <h4 class="font-semibold text-sm">Investment Tracking</h4>
              <p class="text-sm text-muted-foreground">Record individual investments and calculate profit shares automatically</p>
            </div>
          </div>
          
          <div class="flex items-start space-x-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10">
              <TrendingUp class="h-4 w-4 text-primary" />
            </div>
            <div>
              <h4 class="font-semibold text-sm">Sales & Profit Analytics</h4>
              <p class="text-sm text-muted-foreground">Track sales, calculate profits, and monitor performance metrics</p>
            </div>
          </div>
          
          <div class="flex items-start space-x-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10">
              <Users class="h-4 w-4 text-primary" />
            </div>
            <div>
              <h4 class="font-semibold text-sm">Team Management</h4>
              <p class="text-sm text-muted-foreground">Manage family members and ownership percentages with automatic profit distribution</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Separator />

      <!-- Next Steps Card -->
      <Card>
        <CardHeader class="mb-2">
          <CardTitle class="flex items-center gap-2">
            <BarChart3 class="h-5 w-5" />
            What's Next?
          </CardTitle>
          <CardDescription>
            After creating your business, you can:
          </CardDescription>
        </CardHeader>
        <CardContent class="space-y-3">
          <div class="flex items-start space-x-3 p-3 rounded-lg border bg-muted/50">
            <div class="w-2 h-2 bg-primary rounded-full mt-2 flex-shrink-0"></div>
            <div>
              <p class="text-sm font-medium">Create Product Batches</p>
              <p class="text-xs text-muted-foreground">Add products with costs and quantities to track inventory</p>
            </div>
          </div>
          
          <div class="flex items-start space-x-3 p-3 rounded-lg border bg-muted/50">
            <div class="w-2 h-2 bg-primary rounded-full mt-2 flex-shrink-0"></div>
            <div>
              <p class="text-sm font-medium">Record Investments</p>
              <p class="text-xs text-muted-foreground">Add family investments with automatic share calculations</p>
            </div>
          </div>
          
          <div class="flex items-start space-x-3 p-3 rounded-lg border bg-muted/50">
            <div class="w-2 h-2 bg-primary rounded-full mt-2 flex-shrink-0"></div>
            <div>
              <p class="text-sm font-medium">Track Sales</p>
              <p class="text-xs text-muted-foreground">Record sales transactions and monitor revenue growth</p>
            </div>
          </div>
          
          <div class="flex items-start space-x-3 p-3 rounded-lg border bg-muted/50">
            <div class="w-2 h-2 bg-primary rounded-full mt-2 flex-shrink-0"></div>
            <div>
              <p class="text-sm font-medium">Invite Team Members</p>
              <p class="text-xs text-muted-foreground">Add family members and set ownership percentages</p>
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