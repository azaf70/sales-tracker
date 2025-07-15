<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Plus, ShoppingCart } from 'lucide-vue-next'
import { formatDateTime } from '@/lib/date-utils'

interface Sale {
  id: number
  product_batch: { id: number; name: string }
  quantity: number
  sale_price: number
  sold_at: string
}

interface Business {
  id: number
  name: string
}

interface Props {
  business: Business
  sales: Sale[]
}

const props = defineProps<Props>()

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('en-GB', {
    style: 'currency',
    currency: 'GBP',
  }).format(amount)
}

// Using formatDateTime from date-utils
</script>

<template>
  <div class="space-y-4 p-4">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h1 class="text-xl font-bold tracking-tight">Sales</h1>
        <p class="text-sm text-muted-foreground">All sales for {{ props.business.name }}</p>
      </div>
      <Link :href="route('businesses.sales.create', props.business.id)" as="button">
        <Button size="sm">
          <Plus class="h-4 w-4 mr-2" />
          Record Sale
        </Button>
      </Link>
    </div>

    <Card v-if="props.sales.length === 0" class="text-center py-12">
      <CardContent>
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-muted flex items-center justify-center">
          <ShoppingCart class="w-10 h-10" />
        </div>
        <h2 class="text-xl font-bold mb-2">No sales found</h2>
        <p class="text-muted-foreground mb-8">
          Record your first sale to start tracking revenue and profit.
        </p>
        <Button as-child>
          <Link :href="route('businesses.sales.create', props.business.id)">
            <Plus class="w-4 h-4 mr-2" />
            Record Sale
          </Link>
        </Button>
      </CardContent>
    </Card>

    <div v-else class="space-y-4">
      <Card v-for="sale in props.sales" :key="sale.id">
        <CardContent class="p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
              <Badge variant="secondary">
                {{ sale.product_batch.name }}
              </Badge>
            </div>
            <div class="text-sm text-muted-foreground">
              Sold: {{ formatDateTime(sale.sold_at) }}
            </div>
          </div>
          <div class="flex flex-col items-end">
            <div class="font-bold text-lg text-green-600">{{ formatCurrency(sale.sale_price) }}</div>
            <div class="text-xs text-muted-foreground">Qty: {{ sale.quantity }}</div>
          </div>
        </CardContent>
      </Card>
    </div>
  </div>
</template> 