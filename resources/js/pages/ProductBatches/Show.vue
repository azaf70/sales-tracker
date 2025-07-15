<template>
  <AppLayout :title="`Batch: ${batch.batch.name}`">
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ batch.batch.name }} - {{ business.name }}
        </h2>
        <div class="flex space-x-2">
          <Link
            :href="route('businesses.product-batches.index', business.id)"
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
          >
            Back to Batches
          </Link>
          <Link
            v-if="batch.batch.status === 'active'"
            :href="route('businesses.product-batches.edit', [business.id, batch.batch.id])"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
          >
            Edit Batch
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Batch Overview -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-lg font-semibold text-gray-900">Batch Overview</h3>
              <Badge :variant="getStatusVariant(batch.batch.status)">
                {{ batch.batch.status }}
              </Badge>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              <!-- Progress -->
              <div class="bg-blue-50 p-4 rounded-lg">
                <h4 class="text-sm font-medium text-blue-800">Progress</h4>
                <p class="text-2xl font-bold text-blue-900">{{ batch.batch.sold_percentage }}%</p>
                <div class="w-full bg-blue-200 rounded-full h-2 mt-2">
                  <div
                    class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                    :style="{ width: `${batch.batch.sold_percentage}%` }"
                  ></div>
                </div>
                <p class="text-sm text-blue-700 mt-1">
                  {{ batch.batch.remaining_quantity }} / {{ batch.batch.total_quantity }} remaining
                </p>
              </div>

              <!-- Revenue -->
              <div class="bg-green-50 p-4 rounded-lg">
                <h4 class="text-sm font-medium text-green-800">Total Revenue</h4>
                <p class="text-2xl font-bold text-green-900">£{{ formatCurrency(batch.batch.total_revenue) }}</p>
              </div>

              <!-- Profit -->
              <div class="bg-purple-50 p-4 rounded-lg">
                <h4 class="text-sm font-medium text-purple-800">Total Profit</h4>
                <p class="text-2xl font-bold" :class="batch.batch.total_profit >= 0 ? 'text-purple-900' : 'text-red-600'">
                  £{{ formatCurrency(batch.batch.total_profit) }}
                </p>
              </div>

              <!-- Investment -->
              <div class="bg-orange-50 p-4 rounded-lg">
                <h4 class="text-sm font-medium text-orange-800">Total Investment</h4>
                <p class="text-2xl font-bold text-orange-900">£{{ formatCurrency(batch.total_investment) }}</p>
              </div>
            </div>

            <!-- Batch Details -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Batch Details</h4>
                <dl class="space-y-2 text-sm">
                  <div class="flex justify-between">
                    <dt class="text-gray-500">Purchase Date:</dt>
                    <dd class="font-medium">{{ formatDate(batch.batch.purchase_date) }}</dd>
                  </div>
                  <div class="flex justify-between">
                    <dt class="text-gray-500">Total Cost:</dt>
                    <dd class="font-medium">£{{ formatCurrency(batch.batch.total_cost) }}</dd>
                  </div>
                  <div v-if="batch.batch.completion_date" class="flex justify-between">
                    <dt class="text-gray-500">Completion Date:</dt>
                    <dd class="font-medium">{{ formatDate(batch.batch.completion_date) }}</dd>
                  </div>
                </dl>
              </div>

              <!-- Actions -->
              <div class="flex flex-col space-y-2">
                <form
                  v-if="batch.batch.status === 'active' && batch.batch.remaining_quantity <= 0"
                  :action="route('businesses.product-batches.complete', [business.id, batch.batch.id])"
                  method="POST"
                  class="inline"
                >
                  <Button type="submit" class="w-full bg-green-600 hover:bg-green-700">
                    Mark as Completed
                  </Button>
                </form>

                <form
                  :action="route('businesses.product-batches.calculate-shares', [business.id, batch.batch.id])"
                  method="POST"
                  class="inline"
                >
                  <Button type="submit" class="w-full bg-blue-600 hover:bg-blue-700">
                    Calculate Investor Shares
                  </Button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Investor Shares -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
          <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Investor Shares</h3>
            <div v-if="batch.investors.length === 0" class="flex flex-col items-center justify-center py-12">
              <div class="w-20 h-20 mb-6 rounded-full bg-muted flex items-center justify-center">
                <DollarSign class="w-10 h-10 text-primary" />
              </div>
              <h2 class="text-xl font-bold mb-2">No investors found for this batch</h2>
              <p class="text-muted-foreground mb-8">Add your first investor to track capital and shares for this batch.</p>
              <Button as-child>
                <Link :href="route('businesses.investments.create', business.id)">
                  <Plus class="w-4 h-4 mr-2" />
                  Add Investor
                </Link>
              </Button>
            </div>
            <div v-else class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Investor
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Investment Amount
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Share Percentage
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Profit Share
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Invested Date
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="investor in batch.investors" :key="investor.user_id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ investor.user_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">£{{ formatCurrency(investor.investment_amount) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ investor.share_percentage }}%</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium" :class="investor.profit_share >= 0 ? 'text-green-600' : 'text-red-600'">
                        £{{ formatCurrency(investor.profit_share) }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ formatDate(investor.invested_at) }}</div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- Sales Section (if applicable) -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
          <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Sales</h3>
            <div v-if="!batch.sales || batch.sales.length === 0" class="flex flex-col items-center justify-center py-12">
              <div class="w-20 h-20 mb-6 rounded-full bg-muted flex items-center justify-center">
                <TrendingUp class="w-10 h-10 text-primary" />
              </div>
              <h2 class="text-xl font-bold mb-2">No sales recorded for this batch</h2>
              <p class="text-muted-foreground mb-8">Record your first sale for this batch to start tracking revenue and performance.</p>
              <Button as-child>
                <Link :href="route('businesses.sales.create', business.id)">
                  <Plus class="w-4 h-4 mr-2" />
                  Record Sale
                </Link>
              </Button>
            </div>
            <div v-else class="overflow-x-auto">
              <!-- ... sales table/list if implemented ... -->
            </div>
          </div>
        </div>
        <!-- FABs for Add Investor and Record Sale -->
        <div class="fixed bottom-24 right-6 z-50 flex flex-col gap-4">
          <Button 
            class="rounded-full h-16 w-16 shadow-lg bg-green-600 text-white"
            :href="route('businesses.investments.create', business.id)"
            as="a"
            title="Add Investor"
          >
            <Plus class="h-7 w-7" />
          </Button>
          <Button 
            class="rounded-full h-16 w-16 shadow-lg bg-orange-600 text-white"
            :href="route('businesses.sales.create', business.id)"
            as="a"
            title="Record Sale"
          >
            <Plus class="h-7 w-7" />
          </Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import Button from '@/components/ui/button/Button.vue'
import { DollarSign, Plus, TrendingUp } from 'lucide-vue-next'
import { formatDate } from '@/lib/date-utils'

interface Investor {
  user_id: number
  user_name: string
  investment_amount: number
  share_percentage: number
  profit_share: number
  invested_at: string
}

interface BatchData {
  batch: {
    id: number
    name: string
    status: string
    total_quantity: number
    remaining_quantity: number
    sold_percentage: number
    total_cost: number
    total_revenue: number
    total_profit: number
    purchase_date: string
    completion_date: string | null
  }
  investors: Investor[]
  total_investment: number
  is_completed: boolean
}

interface Business {
  id: number
  name: string
}

interface Props {
  business: Business
  batch: BatchData
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