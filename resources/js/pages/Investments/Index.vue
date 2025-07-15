<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Plus, DollarSign, Users } from 'lucide-vue-next'
import { formatDate } from '@/lib/date-utils'

interface Investment {
  id: number
  user: { id: number; name: string }
  product_batch?: { id: number; name: string }
  amount: number
  share_percentage: number
  invested_at: string
}

interface Business {
  id: number
  name: string
}

interface Props {
  business: Business
  investments: Investment[]
}

const props = defineProps<Props>()

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('en-GB', {
    style: 'currency',
    currency: 'GBP',
  }).format(amount)
}

// Using formatDate from date-utils
</script>

<template>
  <div class="space-y-4 p-4">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h1 class="text-xl font-bold tracking-tight">Investments</h1>
        <p class="text-sm text-muted-foreground">All investments for {{ props.business.name }}</p>
      </div>
      <Link :href="route('businesses.investments.create', props.business.id)" as="button">
        <Button size="sm">
          <Plus class="h-4 w-4 mr-2" />
          Add Investment
        </Button>
      </Link>
    </div>

    <Card v-if="props.investments.length === 0" class="text-center py-12">
      <CardContent>
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-muted flex items-center justify-center">
          <DollarSign class="w-10 h-10" />
        </div>
        <h2 class="text-xl font-bold mb-2">No investments found</h2>
        <p class="text-muted-foreground mb-8">
          Add your first investment to start tracking business growth.
        </p>
        <Button as-child>
          <Link :href="route('businesses.investments.create', props.business.id)">
            <Plus class="w-4 h-4 mr-2" />
            Add Investment
          </Link>
        </Button>
      </CardContent>
    </Card>

    <div v-else class="space-y-4">
      <Card v-for="investment in props.investments" :key="investment.id">
        <CardContent class="p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
              <Users class="h-4 w-4 text-primary" />
              <span class="font-semibold">{{ investment.user.name }}</span>
              <Badge v-if="investment.product_batch" variant="secondary">
                {{ investment.product_batch.name }}
              </Badge>
            </div>
            <div class="text-sm text-muted-foreground">
              Invested: {{ formatDate(investment.invested_at) }}
            </div>
          </div>
          <div class="flex flex-col items-end">
            <div class="font-bold text-lg text-green-600">{{ formatCurrency(investment.amount) }}</div>
            <div class="text-xs text-muted-foreground">Share: {{ investment.share_percentage }}%</div>
          </div>
        </CardContent>
      </Card>
    </div>
  </div>
</template> 