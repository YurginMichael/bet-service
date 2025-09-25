<template>
  <div class="bet-form">
    <h3>Создать ставку</h3>
    <form @submit.prevent="submitBet">
      <div class="form-group">
        <label for="event">Событие:</label>
        <select id="event" v-model="form.event_id" required @change="updateOutcomes">
          <option value="">Выберите событие</option>
          <option 
            v-for="event in availableEvents" 
            :key="event.id" 
            :value="event.id"
          >
            {{ event.title }}
          </option>
        </select>
      </div>

      <div class="form-group" v-if="selectedEvent">
        <label for="outcome">Исход:</label>
        <select id="outcome" v-model="form.outcome" required>
          <option value="">Выберите исход</option>
          <option 
            v-for="(coefficient, outcome) in selectedEvent.outcomes" 
            :key="outcome" 
            :value="outcome"
          >
            {{ outcome }} (коэф. {{ coefficient }})
          </option>
        </select>
      </div>

      <div class="form-group">
        <label for="amount">Сумма ставки:</label>
        <input 
          id="amount"
          type="number" 
          v-model.number="form.amount" 
          :min="1" 
          :max="userBalance"
          step="1"
          required
          placeholder="Введите сумму"
        />
        <small class="help-text">
          Доступно: {{ formatBalance(userBalance) }}₽
        </small>
      </div>

      <div class="potential-win" v-if="form.outcome && form.amount && selectedEvent">
        <strong>
          Потенциальный выигрыш: {{ formatBalance(potentialWin) }}₽
        </strong>
      </div>

      <button 
        type="submit" 
        :disabled="loading || !canSubmit"
        class="submit-btn"
      >
        {{ loading ? 'Создание ставки...' : 'Создать ставку' }}
      </button>
    </form>

    <div v-if="error" class="error">
      {{ error }}
    </div>

    <div v-if="success" class="success">
      {{ success }}
    </div>
  </div>
</template>

<script>
import { eventsAPI, betsAPI } from '../services/api'
import { formatBalance } from '../utils/formatters'

export default {
  name: 'BetForm',
  props: {
    userBalance: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      events: [],
      form: {
        event_id: '',
        outcome: '',
        amount: ''
      },
      loading: false,
      error: '',
      success: ''
    }
  },
  computed: {
    availableEvents() {
      return this.events.filter(event => event.available_for_betting)
    },
    selectedEvent() {
      return this.events.find(event => event.id === parseInt(this.form.event_id))
    },
    potentialWin() {
      if (!this.selectedEvent || !this.form.outcome || !this.form.amount) {
        return 0
      }
      const coefficient = this.selectedEvent.outcomes[this.form.outcome]
      return this.form.amount * coefficient
    },
    canSubmit() {
      return this.form.event_id && 
             this.form.outcome && 
             this.form.amount > 0 && 
             this.form.amount <= this.userBalance
    }
  },
  async mounted() {
    await this.loadEvents()
  },
  methods: {
    async loadEvents() {
      try {
        const response = await eventsAPI.getAll()
        this.events = response.data.events || []
      } catch (error) {
        this.error = 'Ошибка загрузки событий'
        console.error('Error loading events:', error)
      }
    },
    updateOutcomes() {
      this.form.outcome = ''
    },
    async submitBet() {
      this.loading = true
      this.error = ''
      this.success = ''

      try {
        const response = await betsAPI.create({
          event_id: parseInt(this.form.event_id),
          outcome: this.form.outcome,
          amount: parseFloat(this.form.amount)
        })

        this.success = `Ставка создана! Потенциальный выигрыш: ${formatBalance(response.data.bet.potential_win)}₽`
        
        this.resetForm()
        this.$emit('bet-created', response.data.balance)
        await this.loadEvents()

      } catch (error) {
        if (error.response?.data?.error) {
          this.error = error.response.data.error
        } else {
          this.error = 'Ошибка создания ставки'
        }
        console.error('Error creating bet:', error)
      } finally {
        this.loading = false
      }
    },
    resetForm() {
      this.form = {
        event_id: '',
        outcome: '',
        amount: ''
      }
    },
    formatBalance
  }
}
</script>

<style scoped>
.bet-form {
  background: white;
  padding: 20px;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
  color: #374151;
}

input, select {
  width: 100%;
  padding: 10px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  box-sizing: border-box;
  font-size: 14px;
}

input:focus, select:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.help-text {
  display: block;
  margin-top: 5px;
  color: #6b7280;
  font-size: 12px;
}

.potential-win {
  margin: 15px 0;
  padding: 10px;
  background: #f0f9ff;
  border-radius: 6px;
  color: #0369a1;
}

.submit-btn {
  width: 100%;
  padding: 12px;
  background: #059669;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 16px;
  font-weight: bold;
}

.submit-btn:hover:not(:disabled) {
  background: #047857;
}

.submit-btn:disabled {
  background: #9ca3af;
  cursor: not-allowed;
}

.error {
  margin-top: 15px;
  padding: 10px;
  background: #fef2f2;
  border: 1px solid #fecaca;
  border-radius: 6px;
  color: #dc2626;
}

.success {
  margin-top: 15px;
  padding: 10px;
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  border-radius: 6px;
  color: #059669;
}
</style>