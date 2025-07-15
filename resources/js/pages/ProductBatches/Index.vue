<template>
  <AppLayout title="Product Batches">
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Product Batches - {{ business.name }}
        </h2>
        <Link
          :href="route('businesses.product-batches.create', business.id)"
          class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
          Create Batch
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <div v-if="batches.length === 0" class="flex flex-col items-center justify-center py-12">
              <div class="w-20 h-20 mb-6 rounded-full bg-muted flex items-center justify-center">
                <Package class="w-10 h-10 text-primary" />
              </div>
              <h2 class="text-xl font-bold mb-2">No product batches found</h2>
              <p class="text-muted-foreground mb-8">Create your first batch to start tracking inventory and sales.</p>
              <Button as-child>
                <Link :href="route('businesses.product-batches.create', business.id)">
                  <Plus class="w-4 h-4 mr-2" />
                  Create Your First Batch
                </Link>
              </Button>
            </div>
            <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
              <div
                v-for="batch in batches"
                :key="batch.id"
                class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow"
              >
                <div class="p-6">
                  <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ batch.name }}</h3>
                    <Badge :variant="getStatusVariant(batch.status)">
                      {{ batch.status }}
                    </Badge>
                  </div>
                  <div class="space-y-3">
                    <!-- Progress Bar -->
                    <div>
                      <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Progress</span>
                        <span>{{ batch.sold_percentage }}%</span>
                      </div>
                      <div class="w-full bg-gray-200 rounded-full h-2">
                        <div
                          class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                          :style="{ width: `${batch.sold_percentage}%` }"
                        ></div>
                      </div>
                    </div>
                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-4 text-sm">
                      <div>
                        <p class="text-gray-500">Quantity</p>
                        <p class="font-medium">{{ batch.remaining_quantity }} / {{ batch.total_quantity }}</p>
                      </div>
                      <div>
                        <p class="text-gray-500">Revenue</p>
                        <p class="font-medium">£{{ formatCurrency(batch.total_revenue) }}</p>
                      </div>
                      <div>
                        <p class="text-gray-500">Profit</p>
                        <p class="font-medium" :class="batch.total_profit >= 0 ? 'text-green-600' : 'text-red-600'">
                          £{{ formatCurrency(batch.total_profit) }}
                        </p>
                      </div>
                      <div>
                        <p class="text-gray-500">Investors</p>
                        <p class="font-medium">{{ batch.investors_count }}</p>
                      </div>
                    </div>
                    <!-- Dates -->
                    <div class="text-sm text-gray-500">
                      <p>Purchased: {{ formatDate(batch.purchase_date) }}</p>
                      <p v-if="batch.completion_date">Completed: {{ formatDate(batch.completion_date) }}</p>
                    </div>
                    <!-- Actions -->
                    <div class="flex space-x-2 pt-4">
                      <Link
                        :href="route('businesses.product-batches.show', [business.id, batch.id])"
                        class="flex-1 text-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors"
                      >
                        View Details
                      </Link>
                      <Link
                        v-if="batch.status === 'active'"
                        :href="route('businesses.product-batches.edit', [business.id, batch.id])"
                        class="flex-1 text-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-md hover:bg-gray-100 transition-colors"
                      >
                        Edit
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- FAB for Create Batch -->
    <div class="fixed bottom-24 right-6 z-50">
      <Button 
        class="rounded-full h-16 w-16 shadow-lg bg-primary text-white"
        :href="route('businesses.product-batches.create', business.id)"
        as="a"
        title="Create Batch"
      >
        <Plus class="h-7 w-7" />
      </Button>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import { Button } from '@/components/ui/button'
import { Package, Plus } from 'lucide-vue-next'
import { formatDate } from '@/lib/date-utils'

interface Batch {
  id: number
  name: string
  status: 'active' | 'completed' | 'cancelled'
  total_quantity: number
  remaining_quantity: number
  sold_percentage: number
  total_cost: number
  total_revenue: number
  total_profit: number
  purchase_date: string
  completion_date: string | null
  investors_count: number
  total_investment: number
}

interface Business {
  id: number
  name: string
}

interface Props {
  business: Business
  batches: Batch[]
}

const props = defineProps<Props>()

const getStatusVariant = (status: string) => {
  switch (status) {
    case 'completed':
      return 'success'
    case 'cancelled':
      return 'destructive'
    default:
      return 'default'
  }
}

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('en-GB', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(amount)
}

// Using formatDate from date-utils
</script> 