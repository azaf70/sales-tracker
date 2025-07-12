<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
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
import { Plus, Users, TrendingUp, PoundSterling, UserPlus, Trash2, X, ChevronRight, Building2, DollarSign, Target, ArrowUpRight, ArrowDownRight } from 'lucide-vue-next'

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
const activeTab = ref('overview')
const isLoaded = ref(false)

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
  setTimeout(() => {
    isLoaded.value = true
  }, 100)
})
</script>

<template>
  <AppMobileLayout>
    <!-- Hero Header with Glassmorphism -->
    <div class="relative overflow-hidden">
      <!-- Background Gradient -->
      <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800"></div>
      
      <!-- Animated Background Elements -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-24 h-24 bg-cyan-400 rounded-full blur-2xl animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/2 w-40 h-40 bg-purple-400 rounded-full blur-3xl animate-pulse delay-500"></div>
      </div>

      <!-- Content -->
      <div class="relative z-10 px-6 py-8">
        <div class="flex items-center justify-between mb-6">
          <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-white mb-2 tracking-tight">
              {{ business.name }}
            </h1>
            <p v-if="business.description" class="text-blue-100 text-lg leading-relaxed">
              {{ business.description }}
            </p>
          </div>
          <div class="flex items-center space-x-3 ml-4">
            <Link
              v-if="isOwner"
              :href="route('businesses.edit', business.id)"
              as="button"
            >
              <Button variant="secondary" size="sm" class="bg-white/20 backdrop-blur-sm border-white/30 text-white hover:bg-white/30">
                Edit
              </Button>
            </Link>
            <Link :href="route('businesses.index')" as="button">
              <Button variant="ghost" size="sm" class="text-white hover:bg-white/20">
                <X class="h-5 w-5" />
              </Button>
            </Link>
          </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-3 gap-4">
          <div class="text-center">
            <div class="text-2xl font-bold text-white">{{ business.members.length }}</div>
            <div class="text-blue-100 text-sm">Members</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-white">{{ formatCurrency(totalProfit) }}</div>
            <div class="text-blue-100 text-sm">Profit</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-white">{{ formatCurrency(totalRevenue) }}</div>
            <div class="text-blue-100 text-sm">Revenue</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab Navigation -->
    <div class="px-6 -mt-4 relative z-20">
      <Tabs v-model="activeTab" class="w-full">
        <TabsList class="grid w-full grid-cols-3 bg-white/10 backdrop-blur-sm border border-white/20">
          <TabsTrigger 
            value="overview" 
            class="data-[state=active]:bg-white data-[state=active]:text-blue-600 data-[state=active]:shadow-lg transition-all duration-300"
          >
            Overview
          </TabsTrigger>
          <TabsTrigger 
            value="members" 
            class="data-[state=active]:bg-white data-[state=active]:text-blue-600 data-[state=active]:shadow-lg transition-all duration-300"
          >
            Members
          </TabsTrigger>
          <TabsTrigger 
            value="investments" 
            class="data-[state=active]:bg-white data-[state=active]:text-blue-600 data-[state=active]:shadow-lg transition-all duration-300"
          >
            Investments
          </TabsTrigger>
        </TabsList>

        <!-- Overview Tab -->
        <TabsContent value="overview" class="space-y-6 pt-6">
          <!-- Key Metrics Cards -->
          <div class="grid grid-cols-2 gap-4">
            <Card class="bg-white/80 backdrop-blur-sm border-0 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
              <CardHeader class="pb-3">
                <div class="flex items-center justify-between">
                  <CardTitle class="text-sm font-medium text-gray-600">Total Investments</CardTitle>
                  <Building2 class="h-5 w-5 text-blue-600" />
                </div>
              </CardHeader>
              <CardContent>
                <div class="text-2xl font-bold text-gray-900">{{ formatCurrency(totalInvestments) }}</div>
                <div class="flex items-center mt-2 text-sm text-green-600">
                  <ArrowUpRight class="h-4 w-4 mr-1" />
                  <span>+12.5%</span>
                </div>
              </CardContent>
            </Card>
            
            <Card class="bg-white/80 backdrop-blur-sm border-0 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
              <CardHeader class="pb-3">
                <div class="flex items-center justify-between">
                  <CardTitle class="text-sm font-medium text-gray-600">Total Revenue</CardTitle>
                  <DollarSign class="h-5 w-5 text-green-600" />
                </div>
              </CardHeader>
              <CardContent>
                <div class="text-2xl font-bold text-gray-900">{{ formatCurrency(totalRevenue) }}</div>
                <div class="flex items-center mt-2 text-sm text-green-600">
                  <ArrowUpRight class="h-4 w-4 mr-1" />
                  <span>+8.3%</span>
                </div>
              </CardContent>
            </Card>
            
            <Card class="bg-white/80 backdrop-blur-sm border-0 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
              <CardHeader class="pb-3">
                <div class="flex items-center justify-between">
                  <CardTitle class="text-sm font-medium text-gray-600">Total Profit</CardTitle>
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
            
            <Card class="bg-white/80 backdrop-blur-sm border-0 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
              <CardHeader class="pb-3">
                <div class="flex items-center justify-between">
                  <CardTitle class="text-sm font-medium text-gray-600">ROI</CardTitle>
                  <TrendingUp class="h-5 w-5 text-purple-600" />
                </div>
              </CardHeader>
              <CardContent>
                <div class="text-2xl font-bold text-gray-900">
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
          <Card class="bg-white/80 backdrop-blur-sm border-0 shadow-xl">
            <CardHeader>
              <CardTitle class="flex items-center gap-3 text-xl font-bold text-gray-900">
                <div class="p-2 bg-gradient-to-br from-green-400 to-blue-500 rounded-lg">
                  <TrendingUp class="h-5 w-5 text-white" />
                </div>
                Profit Distribution
              </CardTitle>
              <CardDescription class="text-gray-600">How profits are distributed among team members</CardDescription>
            </CardHeader>
            <CardContent class="space-y-4">
              <div
                v-for="(memberProfit, index) in memberProfits"
                :key="memberProfit.user.id"
                class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:shadow-md transition-all duration-300"
                :style="{ animationDelay: `${index * 100}ms` }"
              >
                <div class="flex-1 min-w-0">
                  <div class="font-semibold text-gray-900 truncate">{{ memberProfit.user.name }}</div>
                  <div class="text-sm text-gray-500 flex items-center mt-1">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                    {{ memberProfit.ownership_percentage }}% ownership
                  </div>
                </div>
                <div class="text-right ml-4">
                  <div class="font-bold text-lg" :class="memberProfit.profit_share >= 0 ? 'text-green-600' : 'text-red-600'">
                    {{ formatCurrency(memberProfit.profit_share) }}
                  </div>
                  <div class="text-sm text-gray-500">
                    {{ ((memberProfit.profit_share / totalProfit) * 100).toFixed(1) }}% of total
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
        </TabsContent>

        <!-- Members Tab -->
        <TabsContent value="members" class="space-y-6 pt-6">
          <Card class="bg-white/80 backdrop-blur-sm border-0 shadow-xl">
            <CardHeader>
              <div class="flex items-center justify-between">
                <div>
                  <CardTitle class="flex items-center gap-3 text-xl font-bold text-gray-900">
                    <div class="p-2 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg">
                      <Users class="h-5 w-5 text-white" />
                    </div>
                    Team Members
                  </CardTitle>
                  <CardDescription class="text-gray-600">Manage your business team and ownership</CardDescription>
                </div>
                <Sheet v-model:open="showInviteForm">
                  <SheetTrigger as-child>
                    <Button v-if="isOwner" size="sm" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white shadow-lg">
                      <UserPlus class="h-4 w-4 mr-2" />
                      Invite
                    </Button>
                  </SheetTrigger>
                  <SheetContent class="bg-white/95 backdrop-blur-md">
                    <SheetHeader>
                      <SheetTitle class="text-2xl font-bold text-gray-900">Invite New Member</SheetTitle>
                      <SheetDescription class="text-gray-600">
                        Send an invitation to join your business. They'll receive an email with instructions.
                      </SheetDescription>
                    </SheetHeader>
                    <form @submit.prevent="inviteMember" class="space-y-6 mt-8">
                      <div class="space-y-3">
                        <Label for="email" class="text-sm font-medium text-gray-700">Email address</Label>
                        <Input
                          id="email"
                          v-model="inviteForm.email"
                          type="email"
                          placeholder="member@example.com"
                          class="bg-white/50 backdrop-blur-sm border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                          required
                        />
                      </div>
                      <div class="space-y-3">
                        <Label for="ownership_percentage" class="text-sm font-medium text-gray-700">Ownership Percentage</Label>
                        <Input
                          id="ownership_percentage"
                          v-model="inviteForm.ownership_percentage"
                          type="number"
                          step="0.01"
                          min="0"
                          max="100"
                          placeholder="25.00"
                          class="bg-white/50 backdrop-blur-sm border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                          required
                        />
                      </div>
                      <SheetFooter>
                        <Button type="submit" :disabled="inviteForm.processing" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white">
                          Send Invitation
                        </Button>
                      </SheetFooter>
                    </form>
                  </SheetContent>
                </Sheet>
              </div>
            </CardHeader>
            <CardContent class="space-y-4">
              <div
                v-for="(member, index) in business.members"
                :key="member.id"
                class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:shadow-md transition-all duration-300"
                :style="{ animationDelay: `${index * 100}ms` }"
              >
                <div class="flex-1 min-w-0">
                  <div class="font-semibold text-gray-900 truncate">{{ member.user.name }}</div>
                  <div class="text-sm text-gray-500 flex items-center mt-1">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                    {{ member.ownership_percentage }}% ownership
                  </div>
                </div>
                <div class="flex items-center space-x-3 ml-4">
                  <Badge v-if="member.user_id === business.created_by" variant="secondary" class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white">
                    Owner
                  </Badge>
                  <Dialog v-model:open="showRemoveDialog">
                    <DialogTrigger as-child>
                      <Button
                        v-if="isOwner && member.user_id !== business.created_by"
                        variant="ghost"
                        size="sm"
                        @click="removeMember(member)"
                        class="text-red-500 hover:bg-red-50 hover:text-red-600"
                      >
                        <Trash2 class="h-4 w-4" />
                      </Button>
                    </DialogTrigger>
                    <DialogContent class="bg-white/95 backdrop-blur-md">
                      <DialogHeader>
                        <DialogTitle class="text-2xl font-bold text-gray-900">Remove Member</DialogTitle>
                        <DialogDescription class="text-gray-600">
                          Are you sure you want to remove {{ memberToRemove?.user.name }} from this business? 
                          This action cannot be undone.
                        </DialogDescription>
                      </DialogHeader>
                      <DialogFooter>
                        <Button variant="outline" @click="showRemoveDialog = false" class="border-gray-200 text-gray-700">
                          Cancel
                        </Button>
                        <Button variant="destructive" @click="confirmRemoveMember" class="bg-red-600 hover:bg-red-700">
                          Remove Member
                        </Button>
                      </DialogFooter>
                    </DialogContent>
                  </Dialog>
                </div>
              </div>
            </CardContent>
          </Card>
        </TabsContent>

        <!-- Investments Tab -->
        <TabsContent value="investments" class="space-y-6 pt-6">
          <Card class="bg-white/80 backdrop-blur-sm border-0 shadow-xl">
            <CardHeader>
              <CardTitle class="flex items-center gap-3 text-xl font-bold text-gray-900">
                <div class="p-2 bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg">
                  <PoundSterling class="h-5 w-5 text-white" />
                </div>
                Investment History
              </CardTitle>
              <CardDescription class="text-gray-600">Track all investments made in this business</CardDescription>
            </CardHeader>
            <CardContent>
              <div class="text-center py-12 text-gray-500">
                <div class="p-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                  <PoundSterling class="h-8 w-8 text-gray-400" />
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Investment tracking coming soon</h3>
                <p class="text-gray-500">We're working on bringing you detailed investment tracking and analytics.</p>
              </div>
            </CardContent>
          </Card>
        </TabsContent>
      </Tabs>
    </div>

    <!-- Floating Action Button -->
    <div class="fixed bottom-24 right-6 z-50">
      <Sheet v-model:open="showInviteForm">
        <SheetTrigger as-child>
          <Button 
            v-if="isOwner" 
            size="lg" 
            class="rounded-full h-16 w-16 shadow-2xl bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white hover:scale-110 transition-all duration-300"
          >
            <Plus class="h-7 w-7" />
          </Button>
        </SheetTrigger>
      </Sheet>
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
</style> 