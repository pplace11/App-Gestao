<template>
  <div class="tenant-active-select">
    <span class="tenant-active-label">TENANT ATIVO:</span>
    <select
      class="tenant-active-select-input"
      :id="id"
      v-model="modelValueProxy"
    >
      <option v-if="tenants.length === 0" value="">Sem tenants</option>
      <option v-for="tenant in tenants" :key="tenant.id" :value="tenant.id">
        {{ tenant.name }} ({{ tenant.slug }})
      </option>
    </select>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue';
const props = defineProps({
  tenants: { type: Array, required: true },
  modelValue: { type: Number, required: true },
  id: { type: String, default: 'active-tenant-select' }
});
const emit = defineEmits(['update:modelValue']);
const modelValueProxy = computed({
  get: () => props.modelValue,
  set: (val) => emit('update:modelValue', val)
});
const selectedTenantLabel = computed(() => {
  const t = props.tenants.find(t => t.id == modelValueProxy.value);
  return t ? `${t.name} (${t.slug})` : 'Selecione um tenant';
});
</script>

<style scoped>
.tenant-active-select {
  display: flex;
  align-items: center;
  gap: 8px;
}
.tenant-active-label {
  color: #e2e8f0;
  font-size: 0.95rem;
}
.tenant-active-select-input {
  padding: 6px 12px;
  border-radius: 6px;
  background: #1e293b;
  color: #e2e8f0;
  border: 1px solid #334155;
  min-width: 180px;
  outline: none;
  box-shadow: none;
  cursor: pointer;
}
</style>
