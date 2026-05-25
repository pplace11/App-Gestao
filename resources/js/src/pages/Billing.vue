<template>
  <div class="billing">
    <OnboardingWizard v-if="showOnboarding" />
    <PlanDashboard />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useTenantStore } from '../stores/tenantStore';
import OnboardingWizard from '../componenjs/OnboardingWizard.vue';
import PlanDashboard from '../componenjs/PlanDashboard.vue';

const tenantStore = useTenantStore();
const showOnboarding = computed(() => {
  const onboarding = tenantStore.activeTenant?.settings?.onboarding;
  if (!onboarding) return true;
  return !onboarding.branding || !onboarding.users || !onboarding.permissions;
});
</script>

<style scoped>
.billing {
  padding: 20px;
}

.billing li {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}
</style>
