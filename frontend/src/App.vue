<template>
  <div class="wrap">
    <h1>Ставки</h1>
    <section class="card">
      <form @submit.prevent="submit">
        <label>
          Событие
          <select v-model="form.event_id">
            <option disabled value="">Выберите событие</option>
            <option v-for="e in events" :key="e.id" :value="e.id">{{ e.name }}</option>
          </select>
        </label>
        <label>
          Исход
          <select v-model="form.outcome">
            <option value="win">Победа</option>
            <option value="lose">Поражение</option>
          </select>
        </label>
        <label>
          Сумма
          <input type="number" min="1" step="1" v-model.number="form.amount" />
        </label>
        <button :disabled="submitting">Поставить</button>
      </form>
      <p v-if="error" class="error">{{ error }}</p>
      <p v-if="ok" class="ok">Ставка создана</p>
    </section>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      events: [],
      form: { event_id: '', outcome: 'win', amount: 0 },
      submitting: false,
      error: '',
      ok: false
    }
  },
  async created() {
    try {
      const { data } = await axios.get('/api/events')
      this.events = data || []
    } catch (_) {
      this.events = []
    }
  },
  methods: {
    async submit() {
      this.error = ''
      this.ok = false
      if (!this.form.event_id || !this.form.amount || this.form.amount <= 0) {
        this.error = 'Проверьте данные'
        return
      }
      this.submitting = true
      try {
        await axios.post('/api/bets', this.form, {
          headers: {
            'Idempotency-Key': Date.now().toString()
          }
        })
        this.ok = true
      } catch (e) {
        this.error = (e.response && e.response.data && e.response.data.message) || 'Ошибка'
      } finally {
        this.submitting = false
      }
    }
  }
}
</script>

<style>
.wrap{max-width:560px;margin:40px auto;padding:0 16px;font-family:Arial,Helvetica,sans-serif}
.card{border:1px solid #e5e7eb;border-radius:8px;padding:16px;background:#fff}
label{display:block;margin-bottom:12px}
input,select{width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px}
button{padding:10px 16px;border:0;border-radius:6px;background:#2563eb;color:#fff;cursor:pointer}
.error{color:#b91c1c;margin-top:8px}
.ok{color:#047857;margin-top:8px}
</style>
