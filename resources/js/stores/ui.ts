import { defineStore } from 'pinia'

export const useUiStore = defineStore('ui', {
  state: () => ({
    businessTabs: {} as Record<number, string>, // { [businessId]: activeTab }
  }),
  actions: {
    setBusinessTab(businessId: number, tab: string) {
      this.businessTabs[businessId] = tab
    },
    getBusinessTab(businessId: number) {
      return this.businessTabs[businessId] || 'overview'
    }
  }
}) 