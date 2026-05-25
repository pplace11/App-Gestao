<template>
  <div>
    <label for="plan-select">Plano Inicial</label>
    <select id="plan-select" v-model="modelValue" required>
      <option v-for="plan in plans" :key="plan.id" :value="plan.id">
        {{ plan.name }} - R$ {{ plan.monthly_price }} /mês - {{ plan.description || plan.features?.join(', ') }}
      </option>
    </select>
    <div v-if="plans.length === 0">Sem planos disponíveis. Crie planos para continuar.</div>
  </div>
</template>

<script setup>
import { ref, onMounted, defineProps, defineEmits } from 'vue'
const props = defineProps({
  modelValue: [String, Number]
})
const emit = defineEmits(['update:modelValue'])
const plans = ref([])

onMounted(async () => {
  const res = await fetch('/api/v1/plans', {
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token')
    }
  })
  plans.value = await res.json()
})

watch(() => props.modelValue, v => emit('update:modelValue', v))
</script>
