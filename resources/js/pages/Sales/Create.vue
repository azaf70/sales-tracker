<template>
  <AppLayout title="Record Sale">
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
            <BreadcrumbLink :href="route('businesses.show', business.id)">{{ business.name }}</BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator><Slash /></BreadcrumbSeparator>
          <BreadcrumbItem>
            <BreadcrumbLink :href="route('businesses.sales.index', business.id)">Sales</BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator><Slash /></BreadcrumbSeparator>
          <BreadcrumbItem>
            <BreadcrumbLink href="#">Record</BreadcrumbLink>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center gap-2">
            <TrendingUp class="h-5 w-5" />
            Record Sale
          </CardTitle>
          <CardDescription>Record a new sale for {{ business.name }}</CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-4">
            <div class="grid gap-2">
              <Label for="product_batch_id">Product Batch</Label>
              <Select v-model="form.product_batch_id">
                <SelectTrigger id="product_batch_id">
                  <SelectValue placeholder="Select a product batch" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem v-for="batch in business.product_batches" :key="batch.id" :value="batch.id">
                    {{ batch.name }} ({{ batch.remaining_quantity }} remaining)
                  </SelectItem>
                </SelectContent>
              </Select>
              <InputError :message="form.errors.product_batch_id" class="mt-2" />
            </div>
            <div class="grid gap-2">
              <Label for="quantity">Quantity Sold</Label>
              <Input
                id="quantity"
                v-model="form.quantity"
                type="number"
                min="1"
                required
                :class="{ 'border-destructive': form.errors.quantity }"
              />
              <InputError :message="form.errors.quantity" class="mt-2" />
            </div>
            <div class="grid gap-2">
              <Label for="sale_price">Total Sale Price (£)</Label>
              <Input
                id="sale_price"
                v-model="form.sale_price"
                type="number"
                step="0.01"
                min="0"
                required
                :class="{ 'border-destructive': form.errors.sale_price }"
              />
              <InputError :message="form.errors.sale_price" class="mt-2" />
            </div>
            <div class="grid gap-2">
              <Label for="sold_at">Sale Date</Label>
              <Popover>
                <PopoverTrigger as-child>
                  <Button
                    variant="outline"
                    :class="cn(
                      'w-full justify-start text-left font-normal',
                      !form.sold_at && 'text-muted-foreground',
                    )"
                  >
                    <span>{{ form.sold_at || 'Pick a date' }}</span>
                    <CalendarIcon class="ml-auto h-4 w-4 opacity-50" />
                  </Button>
                </PopoverTrigger>
                <PopoverContent class="w-auto p-0">
                  <Calendar
                    v-model="form.sold_at"
                    :class="{ 'border-destructive': form.errors.sold_at }"
                  />
                </PopoverContent>
              </Popover>
              <InputError :message="form.errors.sold_at" class="mt-2" />
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
            {{ form.processing ? 'Recording...' : 'Record Sale' }}
          </Button>
        </CardFooter>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3'
import { Card, CardContent, CardDescription, CardHeader, CardTitle, CardFooter } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Calendar } from '@/components/ui/calendar'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { Breadcrumb, BreadcrumbItem, BreadcrumbLink, BreadcrumbList, BreadcrumbSeparator } from '@/components/ui/breadcrumb'
import { Slash, TrendingUp, Loader2, Sparkles, CalendarIcon } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import InputError from '@/components/InputError.vue'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { cn } from '@/lib/utils'

interface ProductBatch {
  id: number
  name: string
  remaining_quantity: number
}

interface Business {
  id: number
  name: string
  product_batches: ProductBatch[]
}

interface Props {
  business: Business
}

const props = defineProps<Props>()

const form = useForm({
  product_batch_id: '',
  quantity: '',
  sale_price: '',
  sold_at: '',
})

const submit = () => {
  form.post(route('businesses.sales.store', props.business.id))
}

const handleCancel = () => {
  if (window.history.length > 1) {
    window.history.back();
  } else {
    window.location.href = route('businesses.sales.index', props.business.id);
  }
}
</script> 