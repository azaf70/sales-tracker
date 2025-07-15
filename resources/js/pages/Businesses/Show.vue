<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { Link, useForm, usePage, router } from '@inertiajs/vue3'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import { Badge } from '@/components/ui/badge'
import { Sheet, SheetContent, SheetDescription, SheetFooter, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { Separator } from '@/components/ui/separator'
import AppMobileLayout from '@/layouts/app/AppMobileLayout.vue'
import { Plus, Users, TrendingUp, PoundSterling, UserPlus, Trash2, X, ChevronRight, Building2, DollarSign, Target, ArrowUpRight, ArrowDownRight, Package, ShoppingCart, BarChart3 } from 'lucide-vue-next'
import { useUiStore } from '@/stores/ui'
import { formatDate, formatDateTime } from '@/lib/date-utils'

interface Props {
  business: {
    id: number
    name: string
    description?: string
    created_by: number
    members: Array<{
      id: number
      user_id: number
      ownership_percentage: number
      user: {
        id: number
        name: string
        email: string
      }
    }>
    product_batches?: Array<{
      id: number
      name: string
      status: string
      total_quantity: number
      remaining_quantity: number
      total_cost: number
      total_revenue: number
      total_profit: number
      purchase_date: string
      completion_date?: string
    }>
    investments?: Array<{
      id: number
      user: {
        id: number
        name: string
      }
      product_batch?: {
        id: number
        name: string
      }
      amount: number
      share_percentage: number
      invested_at: string
    }>
    sales?: Array<{
      id: number
      product_batch: {
        id: number
        name: string
      }
      quantity: number
      sale_price: number
      sold_at: string
    }>
  }
  totalInvestments: number
  totalRevenue: number
  totalProfit: number
  memberProfits: Array<{
    user: {
      id: number
      name: string
    }
    ownership_percentage: number
    profit_share: number
  }>
}

const props = defineProps<Props>()
const page = usePage()
const showInviteForm = ref(false)
const showRemoveDialog = ref(false)
const memberToRemove = ref<any>(null)
const tabs = [
  { value: 'overview', label: 'Overview', icon: BarChart3 },
  { value: 'batches', label: 'Batches', icon: Package },
  { value: 'investments', label: 'Investments', icon: DollarSign },
  { value: 'sales', label: 'Sales', icon: TrendingUp },
  { value: 'members', label: 'Members', icon: Users },
]
const isLoaded = ref(false)

const ui = useUiStore()
const businessId = props.business.id
const activeTab = ref(ui.getBusinessTab(businessId))

watch(activeTab, (tab) => {
  ui.setBusinessTab(businessId, tab)
})

const inviteForm = useForm({
  email: '',
  ownership_percentage: '',
})

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('en-GB', {
    style: 'currency',
    currency: 'GBP',
  }).format(amount)
}

// Using formatDate from date-utils

const isOwner = computed(() => props.business.created_by === page.props.auth.user.id)

const inviteMember = () => {
  inviteForm.post(route('businesses.members.store', props.business.id), {
    onSuccess: () => {
      showInviteForm.value = false
      inviteForm.reset()
    }
  })
}

const removeMember = (member: any) => {
  memberToRemove.value = member
  showRemoveDialog.value = true
}

const confirmRemoveMember = () => {
  if (memberToRemove.value) {
    router.delete(route('businesses.members.destroy', [props.business.id, memberToRemove.value.id]), {
      onSuccess: () => {
        showRemoveDialog.value = false
        memberToRemove.value = null
      }
    })
  }
}

onMounted(() => {
  // Restore tab from store if available
  activeTab.value = ui.getBusinessTab(businessId)
  setTimeout(() => {
    isLoaded.value = true
  }, 100)
})
</script>

<template>
  <AppMobileLayout>
    <div class="space-y-6 p-4">
      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <div class="flex-1 min-w-0">
          <h1 class="text-xl font-bold tracking-tight">{{ business.name }}</h1>
          <p v-if="business.description" class="text-sm text-muted-foreground">
            {{ business.description }}
          </p>
        </div>
        <div class="flex items-center space-x-3 ml-4">
          <Link
            v-if="isOwner"
            :href="route('businesses.edit', business.id)"
            as="button"
          >
            <Button variant="outline" size="sm">
              Edit
            </Button>
          </Link>
          <Link :href="route('businesses.index')" as="button">
            <Button variant="ghost" size="sm">
              <X class="h-5 w-5" />
            </Button>
          </Link>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="grid grid-cols-3 gap-4">
        <Card>
          <CardContent class="p-4 text-center">
            <div class="text-2xl font-bold">{{ business.members.length }}</div>
            <div class="text-sm text-muted-foreground">Members</div>
          </CardContent>
        </Card>
        <Card>
          <CardContent class="p-4 text-center">
            <div class="text-2xl font-bold">{{ formatCurrency(totalProfit) }}</div>
            <div class="text-sm text-muted-foreground">Profit</div>
          </CardContent>
        </Card>
        <Card>
          <CardContent class="p-4 text-center">
            <div class="text-2xl font-bold">{{ formatCurrency(totalRevenue) }}</div>
            <div class="text-sm text-muted-foreground">Revenue</div>
          </CardContent>
        </Card>
      </div>
      <!-- Tab Navigation -->
      <div class="w-full sticky top-0 z-10 bg-background border-b border-muted/40">
        <div class="flex overflow-x-auto no-scrollbar gap-2 px-1 py-2">
          <button
            v-for="tab in tabs"
            :key="tab.value"
            @click="activeTab = tab.value"
            class="flex flex-col items-center min-w-[80px] px-3 py-2 rounded-full text-xs sm:text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50"
            :class="activeTab === tab.value ? 'bg-primary text-primary-foreground shadow' : 'bg-muted text-muted-foreground'"
            style="border: none; outline: none;"
            type="button"
          >
            <component :is="tab.icon" class="h-5 w-5 mb-1" />
            <span class="truncate">{{ tab.label }}</span>
          </button>
        </div>
      </div>

      <div class="pt-4">
        <div v-if="activeTab === 'overview'">
          <!-- Overview Tab Content -->
          <!-- Key Metrics Cards -->
          <div class="grid grid-cols-2 gap-4">
            <Card>
              <CardHeader class="pb-3">
                <div class="flex items-center justify-between">
                  <CardTitle class="text-sm font-medium">Total Investments</CardTitle>
                  <Building2 class="h-5 w-5 text-primary" />
                </div>
              </CardHeader>
              <CardContent>
                <div class="text-2xl font-bold">{{ formatCurrency(totalInvestments) }}</div>
                <div class="flex items-center mt-2 text-sm text-green-600">
                  <ArrowUpRight class="h-4 w-4 mr-1" />
                  <span>+12.5%</span>
                </div>
              </CardContent>
            </Card>
            
            <Card>
              <CardHeader class="pb-3">
                <div class="flex items-center justify-between">
                  <CardTitle class="text-sm font-medium">Total Revenue</CardTitle>
                  <DollarSign class="h-5 w-5 text-green-600" />
                </div>
              </CardHeader>
              <CardContent>
                <div class="text-2xl font-bold">{{ formatCurrency(totalRevenue) }}</div>
                <div class="flex items-center mt-2 text-sm text-green-600">
                  <ArrowUpRight class="h-4 w-4 mr-1" />
                  <span>+8.3%</span>
                </div>
              </CardContent>
            </Card>
            
            <Card>
              <CardHeader class="pb-3">
                <div class="flex items-center justify-between">
                  <CardTitle class="text-sm font-medium">Total Profit</CardTitle>
                  <Target class="h-5 w-5" :class="totalProfit >= 0 ? 'text-green-600' : 'text-red-600'" />
                </div>
              </CardHeader>
              <CardContent>
                <div class="text-2xl font-bold" :class="totalProfit >= 0 ? 'text-green-600' : 'text-red-600'">
                  {{ formatCurrency(totalProfit) }}
                </div>
                <div class="flex items-center mt-2 text-sm" :class="totalProfit >= 0 ? 'text-green-600' : 'text-red-600'">
                  <component :is="totalProfit >= 0 ? ArrowUpRight : ArrowDownRight" class="h-4 w-4 mr-1" />
                  <span>{{ totalProfit >= 0 ? '+15.2%' : '-3.1%' }}</span>
                </div>
              </CardContent>
            </Card>
            
            <Card>
              <CardHeader class="pb-3">
                <div class="flex items-center justify-between">
                  <CardTitle class="text-sm font-medium">ROI</CardTitle>
                  <TrendingUp class="h-5 w-5 text-primary" />
                </div>
              </CardHeader>
              <CardContent>
                <div class="text-2xl font-bold">
                  {{ totalInvestments > 0 ? ((totalProfit / totalInvestments) * 100).toFixed(1) : '0' }}%
                </div>
                <div class="flex items-center mt-2 text-sm text-green-600">
                  <ArrowUpRight class="h-4 w-4 mr-1" />
                  <span>+2.8%</span>
                </div>
              </CardContent>
            </Card>
          </div>

          <!-- Profit Distribution -->
          <Card>
            <CardHeader class="mb-2">
              <CardTitle class="flex items-center gap-2">
                <TrendingUp class="h-5 w-5" />
                Profit Distribution
              </CardTitle>
              <CardDescription>How profits are distributed among team members</CardDescription>
            </CardHeader>
            <CardContent class="space-y-4">
              <div
                v-for="(memberProfit, index) in memberProfits"
                :key="memberProfit.user.id"
                class="flex items-center justify-between p-4 rounded-lg border bg-muted/50"
              >
                <div class="flex-1 min-w-0">
                  <div class="font-semibold truncate">{{ memberProfit.user.name }}</div>
                  <div class="text-sm text-muted-foreground flex items-center mt-1">
                    <div class="w-2 h-2 bg-primary rounded-full mr-2"></div>
                    {{ memberProfit.ownership_percentage }}% ownership
                  </div>
                </div>
                <div class="text-right ml-4">
                  <div class="font-bold text-lg" :class="memberProfit.profit_share >= 0 ? 'text-green-600' : 'text-red-600'">
                    {{ formatCurrency(memberProfit.profit_share) }}
                  </div>
                  <div class="text-sm text-muted-foreground">
                    {{ ((memberProfit.profit_share / totalProfit) * 100).toFixed(1) }}% of total
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
        <div v-else-if="activeTab === 'batches'">
          <!-- Batches Tab Content -->
          <Card>
            <CardHeader class="mb-2">
              <div class="flex items-center justify-between">
                <div>
                  <CardTitle class="flex items-center gap-2">
                    <Package class="h-5 w-5" />
                    Product Batches
                  </CardTitle>
                  <CardDescription>Manage your product inventory and batches</CardDescription>
                </div>
                <Link :href="route('businesses.product-batches.create', business.id)" as="button">
                  <Button size="sm">
                    <Plus class="h-4 w-4 mr-2" />
                    Create Batch
                  </Button>
                </Link>
              </div>
            </CardHeader>
            <CardContent class="space-y-4">
              <div v-if="!business.product_batches || business.product_batches.length === 0" class="text-center py-12 text-muted-foreground">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-muted mx-auto mb-6">
                  <Package class="h-8 w-8" />
                </div>
                <h3 class="text-lg font-semibold mb-2">No product batches yet</h3>
                <p class="text-muted-foreground mb-4">Create your first product batch to start tracking inventory and costs.</p>
                <Link :href="route('businesses.product-batches.create', business.id)" as="button">
                  <Button>
                    <Plus class="h-4 w-4 mr-2" />
                    Create First Batch
                  </Button>
                </Link>
              </div>
              
              <div v-else class="space-y-4">
                <div
                  v-for="batch in business.product_batches"
                  :key="batch.id"
                  class="p-4 rounded-lg border bg-muted/50"
                >
                  <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold">{{ batch.name }}</h4>
                    <Badge :variant="batch.status === 'completed' ? 'default' : batch.status === 'cancelled' ? 'destructive' : 'secondary'">
                      {{ batch.status }}
                    </Badge>
                  </div>
                  
                  <div class="grid grid-cols-2 gap-4 text-sm mb-3">
                    <div>
                      <p class="text-muted-foreground">Quantity</p>
                      <p class="font-medium">{{ batch.remaining_quantity }} / {{ batch.total_quantity }}</p>
                    </div>
                    <div>
                      <p class="text-muted-foreground">Revenue</p>
                      <p class="font-medium">{{ formatCurrency(batch.total_revenue) }}</p>
                    </div>
                    <div>
                      <p class="text-muted-foreground">Profit</p>
                      <p class="font-medium" :class="batch.total_profit >= 0 ? 'text-green-600' : 'text-red-600'">
                        {{ formatCurrency(batch.total_profit) }}
                      </p>
                    </div>
                    <div>
                      <p class="text-muted-foreground">Cost</p>
                      <p class="font-medium">{{ formatCurrency(batch.total_cost) }}</p>
                    </div>
                  </div>
                  
                  <div class="flex items-center justify-between">
                    <div class="text-xs text-muted-foreground">
                      Purchased: {{ formatDate(batch.purchase_date) }}
                    </div>
                    <Link :href="route('businesses.product-batches.show', [business.id, batch.id])" as="button">
                      <Button variant="outline" size="sm">
                        View Details
                      </Button>
                    </Link>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
          <!-- FAB for Batches -->
          <div class="fixed bottom-24 right-6 z-50" v-if="activeTab === 'batches'">
            <Button 
              class="rounded-full h-16 w-16 shadow-lg bg-primary text-white"
              :href="route('businesses.product-batches.create', business.id)"
              as="a"
              title="Add Batch"
            >
              <Plus class="h-7 w-7" />
            </Button>
          </div>
        </div>

        <div v-else-if="activeTab === 'investments'">
          <!-- Investments Tab Content -->
          <Card>
            <CardHeader class="mb-2">
              <div class="flex items-center justify-between">
                <div>
                  <CardTitle class="flex items-center gap-2">
                    <DollarSign class="h-5 w-5" />
                    Investments
                  </CardTitle>
                  <CardDescription>Track and add investments to your business or batches</CardDescription>
                </div>
                <Link :href="route('businesses.investments.create', business.id)" as="button">
                  <Button size="sm">
                    <Plus class="h-4 w-4 mr-2" />
                    Add Investment
                  </Button>
                </Link>
              </div>
            </CardHeader>
            <CardContent class="space-y-4">
              <div v-if="!business.investments || business.investments.length === 0" class="text-center py-12 text-muted-foreground">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-muted mx-auto mb-6">
                  <DollarSign class="h-8 w-8" />
                </div>
                <h3 class="text-lg font-semibold mb-2">No investments yet</h3>
                <p class="text-muted-foreground mb-4">Add your first investment to start tracking capital and shares.</p>
                <Link :href="route('businesses.investments.create', business.id)" as="button">
                  <Button>
                    <Plus class="h-4 w-4 mr-2" />
                    Add First Investment
                  </Button>
                </Link>
              </div>
              <div v-else class="space-y-4">
                <div
                  v-for="investment in business.investments"
                  :key="investment.id"
                  class="p-4 rounded-lg border bg-muted/50"
                >
                  <div class="flex items-center justify-between mb-2">
                    <div>
                      <div class="font-semibold">{{ investment.user.name }}</div>
                      <div class="text-xs text-muted-foreground">{{ formatDate(investment.invested_at) }}</div>
                    </div>
                    <Badge variant="secondary">{{ investment.share_percentage }}%</Badge>
                  </div>
                  <div class="flex items-center justify-between">
                    <div class="text-sm">Amount</div>
                    <div class="font-bold">{{ formatCurrency(investment.amount) }}</div>
                  </div>
                  <div v-if="investment.product_batch" class="text-xs text-muted-foreground mt-1">
                    Batch: {{ investment.product_batch.name }}
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
          <!-- FAB for Investments -->
          <div class="fixed bottom-24 right-6 z-50" v-if="activeTab === 'investments'">
            <Button 
              class="rounded-full h-16 w-16 shadow-lg bg-green-600 text-white"
              :href="route('businesses.investments.create', business.id)"
              as="a"
              title="Add Investment"
            >
              <Plus class="h-7 w-7" />
            </Button>
          </div>
        </div>

        <div v-else-if="activeTab === 'sales'">
          <!-- Sales Tab Content -->
          <Card>
            <CardHeader class="mb-2">
              <div class="flex items-center justify-between">
                <div>
                  <CardTitle class="flex items-center gap-2">
                    <TrendingUp class="h-5 w-5" />
                    Sales
                  </CardTitle>
                  <CardDescription>Record and view sales for your business</CardDescription>
                </div>
                <Link :href="route('businesses.sales.create', business.id)" as="button">
                  <Button size="sm">
                    <Plus class="h-4 w-4 mr-2" />
                    Record Sale
                  </Button>
                </Link>
              </div>
            </CardHeader>
            <CardContent class="space-y-4">
              <div v-if="!business.sales || business.sales.length === 0" class="text-center py-12 text-muted-foreground">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-muted mx-auto mb-6">
                  <TrendingUp class="h-8 w-8" />
                </div>
                <h3 class="text-lg font-semibold mb-2">No sales yet</h3>
                <p class="text-muted-foreground mb-4">Record your first sale to start tracking revenue and performance.</p>
                <Link :href="route('businesses.sales.create', business.id)" as="button">
                  <Button>
                    <Plus class="h-4 w-4 mr-2" />
                    Record First Sale
                  </Button>
                </Link>
              </div>
              <div v-else class="space-y-4">
                <div
                  v-for="sale in business.sales"
                  :key="sale.id"
                  class="p-4 rounded-lg border bg-muted/50"
                >
                  <div class="flex items-center justify-between mb-2">
                    <div>
                      <div class="font-semibold">Batch: {{ sale.product_batch.name }}</div>
                      <div class="text-xs text-muted-foreground">{{ formatDateTime(sale.sold_at) }}</div>
                    </div>
                    <Badge variant="secondary">{{ sale.quantity }} sold</Badge>
                  </div>
                  <div class="flex items-center justify-between">
                    <div class="text-sm">Sale Price</div>
                    <div class="font-bold">{{ formatCurrency(sale.sale_price) }}</div>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
          <!-- FAB for Sales -->
          <div class="fixed bottom-24 right-6 z-50" v-if="activeTab === 'sales'">
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

        <div v-else-if="activeTab === 'members'">
          <!-- Members Tab Content -->
          <Card>
            <CardHeader class="mb-2">
              <div class="flex items-center justify-between">
                <div>
                  <CardTitle class="flex items-center gap-2">
                    <Users class="h-5 w-5" />
                    Team Members
                  </CardTitle>
                  <CardDescription>Manage your business team and ownership</CardDescription>
                </div>
                <Sheet v-model:open="showInviteForm">
                  <SheetTrigger as-child>
                    <Button v-if="isOwner" size="sm">
                      <UserPlus class="h-4 w-4 mr-2" />
                      Invite
                    </Button>
                  </SheetTrigger>
                  <SheetContent>
                    <SheetHeader>
                      <SheetTitle>Invite New Member</SheetTitle>
                      <SheetDescription>
                        Send an invitation to join your business. They'll receive an email with instructions.
                      </SheetDescription>
                    </SheetHeader>
                    <form @submit.prevent="inviteMember" class="space-y-6 mt-8">
                      <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                          id="email"
                          v-model="inviteForm.email"
                          type="email"
                          placeholder="member@example.com"
                          required
                        />
                      </div>
                      <div class="grid gap-2">
                        <Label for="ownership_percentage">Ownership Percentage</Label>
                        <Input
                          id="ownership_percentage"
                          v-model="inviteForm.ownership_percentage"
                          type="number"
                          step="0.01"
                          min="0"
                          max="100"
                          placeholder="25.00"
                          required
                        />
                      </div>
                      <SheetFooter>
                        <Button type="submit" :disabled="inviteForm.processing">
                          Send Invitation
                        </Button>
                      </SheetFooter>
                    </form>
                  </SheetContent>
                </Sheet>
              </div>
            </CardHeader>
            <CardContent class="space-y-4">
              <div v-if="!business.members || business.members.length === 0" class="text-center py-12 text-muted-foreground">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-muted mx-auto mb-6">
                  <Users class="h-8 w-8" />
                </div>
                <h3 class="text-lg font-semibold mb-2">No team members yet</h3>
                <p class="text-muted-foreground mb-4">Invite your first team member to start collaborating and sharing ownership.</p>
                <Button v-if="isOwner" @click="showInviteForm = true">
                  <UserPlus class="h-4 w-4 mr-2" />
                  Invite First Member
                </Button>
              </div>
              <div v-else>
                <div
                  v-for="(member, index) in business.members"
                  :key="member.id"
                  class="flex items-center justify-between p-4 rounded-lg border bg-muted/50"
                >
                  <div class="flex-1 min-w-0">
                    <div class="font-semibold truncate">{{ member.user.name }}</div>
                    <div class="text-sm text-muted-foreground flex items-center mt-1">
                      <div class="w-2 h-2 bg-primary rounded-full mr-2"></div>
                      {{ member.ownership_percentage }}% ownership
                    </div>
                  </div>
                  <div class="flex items-center space-x-3 ml-4">
                    <Badge v-if="member.user_id === business.created_by" variant="secondary">
                      Owner
                    </Badge>
                    <Dialog v-model:open="showRemoveDialog">
                      <DialogTrigger as-child>
                        <Button
                          v-if="isOwner && member.user_id !== business.created_by"
                          variant="ghost"
                          size="sm"
                          @click="removeMember(member)"
                          class="text-destructive hover:bg-destructive/10"
                        >
                          <Trash2 class="h-4 w-4" />
                        </Button>
                      </DialogTrigger>
                      <DialogContent>
                        <DialogHeader>
                          <DialogTitle>Remove Member</DialogTitle>
                          <DialogDescription>
                            Are you sure you want to remove {{ memberToRemove?.user.name }} from this business? 
                            This action cannot be undone.
                          </DialogDescription>
                        </DialogHeader>
                        <DialogFooter>
                          <Button variant="outline" @click="showRemoveDialog = false">
                            Cancel
                          </Button>
                          <Button variant="destructive" @click="confirmRemoveMember">
                            Remove Member
                          </Button>
                        </DialogFooter>
                      </DialogContent>
                    </Dialog>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
          <!-- FAB for Members -->
          <div class="fixed bottom-24 right-6 z-50" v-if="activeTab === 'members' && isOwner">
            <Button 
              class="rounded-full h-16 w-16 shadow-lg bg-blue-600 text-white"
              @click="showInviteForm = true"
              title="Invite Member"
            >
              <Plus class="h-7 w-7" />
            </Button>
          </div>
        </div>
      </div>
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

.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style> 