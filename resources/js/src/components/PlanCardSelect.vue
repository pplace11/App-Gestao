<template>
  <div>
    <label>Escolha um Plano</label>
    <div class="plan-cards">
      <div
        v-for="plan in plans"
        :key="plan.id"
        :class="['plan-card', { selected: modelValue === plan.id }]"
        @click="selectPlan(plan.id)"
      >
        <h4>{{ plan.name }}</h4>
        <p><strong>Preço:</strong> R$ {{ plan.monthly_price }} / mês</p>
        <p v-if="plan.description"><strong>Descrição:</strong> {{ plan.description }}</p>
        <ul v-if="plan.features && plan.features.length">
          <li v-for="f in plan.features" :key="f">{{ f }}</li>
        </ul>
        <button v-if="modelValue !== plan.id" type="button">Selecionar</button>
        <span v-else class="selected-label">Selecionado</span>
      </div>
    </div>
    <div v-if="plans.length === 0">Sem planos disponíveis. Crie planos para continuar.</div>
  </div>
</template>

<script setup>
import { ref, onMounted, defineProps, defineEmits } from 'vue'
const props = defineProps({ modelValue: [String, Number] })
const emit = defineEmits(['update:modelValue'])
const plans = ref([])

onMounted(async () => {
  const res = await fetch('/api/v1/plans', {
    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
  })
  plans.value = await res.json()
})

function selectPlan(id) {
  emit('update:modelValue', id)
}
</script>

<style scoped>
.plan-cards {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
}
.plan-card {
  background: #22263a;
  color: #fff;
  border: 2px solid #333;
  border-radius: 8px;
  padding: 16px;
  width: 220px;
  cursor: pointer;
  transition: border 0.2s;
}
.plan-card.selected {
  border: 2px solid #007bff;
  background: #181c2a;
}
.selected-label {
  color: #007bff;
  font-weight: bold;
}
.plan-card button {
  margin-top: 10px;
  background: #007bff;
  color: #fff;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
}
</style>
