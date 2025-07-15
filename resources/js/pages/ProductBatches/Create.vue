<template>
  <AppLayout title="Create Product Batch">
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
            <BreadcrumbLink :href="route('businesses.product-batches.index', business.id)">Batches</BreadcrumbLink>
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
            <Package class="h-5 w-5" />
            Create Product Batch
          </CardTitle>
          <CardDescription>Add a new batch to {{ business.name }}</CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-4">
            <div class="grid gap-2">
              <Label for="name">Batch Name</Label>
              <Input
                id="name"
                v-model="form.name"
                type="text"
                placeholder="Enter batch name"
                required
                :class="{ 'border-destructive': form.errors.name }"
              />
              <p v-if="form.errors.name" class="text-sm text-destructive">
                {{ form.errors.name }}
              </p>
            </div>
            <div class="grid gap-2">
              <Label for="purchase_date">Purchase Date</Label>
              <Popover>
                <PopoverTrigger as-child>
                  <Button
                    variant="outline"
                    :class="cn(
                      'w-full justify-start text-left font-normal',
                      !form.purchase_date && 'text-muted-foreground',
                    )"
                  >
                    <span>{{ form.purchase_date || 'Pick a date' }}</span>
                    <CalendarIcon class="ml-auto h-4 w-4 opacity-50" />
                  </Button>
                </PopoverTrigger>
                <PopoverContent class="w-auto p-0">
                  <Calendar
                    v-model="form.purchase_date"
                    :class="{ 'border-destructive': form.errors.purchase_date }"
                  />
                </PopoverContent>
              </Popover>
              <p v-if="form.errors.purchase_date" class="text-sm text-destructive">
                {{ form.errors.purchase_date }}
              </p>
            </div>
            <div class="grid gap-2">
              <Label for="total_cost">Total Cost (£)</Label>
              <Input
                id="total_cost"
                v-model="form.total_cost"
                type="number"
                step="0.01"
                min="0"
                required
                :class="{ 'border-destructive': form.errors.total_cost }"
              />
              <p v-if="form.errors.total_cost" class="text-sm text-destructive">
                {{ form.errors.total_cost }}
              </p>
            </div>
            <div class="grid gap-2">
              <Label for="total_quantity">Total Quantity</Label>
              <Input
                id="total_quantity"
                v-model="form.total_quantity"
                type="number"
                min="1"
                required
                :class="{ 'border-destructive': form.errors.total_quantity }"
              />
              <p v-if="form.errors.total_quantity" class="text-sm text-destructive">
                {{ form.errors.total_quantity }}
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
            {{ form.processing ? 'Creating...' : 'Create Batch' }}
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
import { Slash, Package, Loader2, Sparkles, CalendarIcon } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import AppLayout from '@/layouts/AppLayout.vue'

interface Business {
  id: number
  name: string
}

interface Props {
  business: Business
}

const props = defineProps<Props>()

const form = useForm({
  business_id: props.business.id,
  name: '',
  purchase_date: '',
  total_cost: '',
  total_quantity: '',
})

const submit = () => {
  form.post(route('businesses.product-batches.store', props.business.id))
}

const handleCancel = () => {
  if (window.history.length > 1) {
    window.history.back();
  } else {
    window.location.href = route('businesses.product-batches.index', props.business.id);
  }
}
</script> 