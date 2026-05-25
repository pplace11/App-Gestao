<template>
  <form @submit.prevent="submitPlan" class="plan-create-form">
    <h3>Criar Novo Plano</h3>
    <div>
      <label>Nome</label>
      <input v-model="name" required />
    </div>
    <div>
      <label>Preço Mensal</label>
      <input v-model="monthly_price" type="number" min="0" step="0.01" required />
    </div>
    <div>
      <label>Preço Anual</label>
      <input v-model="yearly_price" type="number" min="0" step="0.01" />
    </div>
    <div>
      <label>Descrição</label>
      <input v-model="description" />
    </div>
    <div>
      <label>Ativo</label>
      <input type="checkbox" v-model="is_active" />
    </div>
    <button type="submit">Criar Plano</button>
    <div v-if="msg" :style="{color: 'green', marginTop: '10px'}">{{ msg }}</div>
    <div v-if="error" :style="{color: 'red', marginTop: '10px'}">{{ error }}</div>
  </form>
</template>

<script setup>
import { ref } from 'vue'
const name = ref('')
const monthly_price = ref('')
const yearly_price = ref('')
const description = ref('')
const is_active = ref(true)
const msg = ref('')
const error = ref('')

async function submitPlan() {
  msg.value = ''
  error.value = ''
  try {
    const res = await fetch('/api/v1/plans', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('token')
      },
      body: JSON.stringify({
        name: name.value,
        monthly_price: monthly_price.value,
        yearly_price: yearly_price.value,
        description: description.value,
        is_active: is_active.value
      })
    })
    if (!res.ok) throw new Error('Erro ao criar plano')
    msg.value = 'Plano criado com sucesso!'
    name.value = ''
    monthly_price.value = ''
    yearly_price.value = ''
    description.value = ''
    is_active.value = true
  } catch (e) {
    error.value = e.message
  }
}
</script>

<style scoped>
.plan-create-form {
  background: #181c2a;
  padding: 20px;
  border-radius: 8px;
  max-width: 400px;
  margin: 20px auto;
  color: #fff;
}
.plan-create-form label {
  display: block;
  margin-bottom: 4px;
}
.plan-create-form input[type="text"],
.plan-create-form input[type="number"] {
  width: 100%;
  margin-bottom: 12px;
  padding: 6px;
  border-radius: 4px;
  border: 1px solid #333;
  background: #22263a;
  color: #fff;
}
.plan-create-form button {
  background: #007bff;
  color: #fff;
  border: none;
  padding: 10px 16px;
  border-radius: 4px;
  cursor: pointer;
}
.plan-create-form button:hover {
  background: #0056b3;
}
</style>
