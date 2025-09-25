<template>
  <div class="bets-list">
    <h3>Мои ставки</h3>
    
    <div v-if="loading" class="loading">
      Загрузка ставок...
    </div>

    <div v-else-if="bets.length === 0" class="empty">
      У вас пока нет ставок
    </div>

    <div v-else class="bets">
      <div 
        v-for="bet in bets" 
        :key="bet.id" 
        class="bet-item"
        :class="getStatusClass(bet.status)"
      >
        <div class="bet-header">
          <span class="bet-id">#{{ bet.id }}</span>
          <span class="bet-status">{{ getStatusText(bet.status) }}</span>
        </div>
        
        <div class="bet-details">
          <div class="event-title">{{ bet.event_title }}</div>
          <div class="bet-outcome">
            <strong>Исход:</strong> {{ bet.outcome }}
          </div>
          <div class="bet-amounts">
            <span class="amount">
              <strong>Ставка:</strong> {{ formatBalance(bet.amount) }}₽
            </span>
            <span class="coefficient">
              <strong>Коэффициент:</strong> {{ bet.coefficient }}
            </span>
            <span class="potential-win">
              <strong>Потенциальный выигрыш:</strong> {{ formatBalance(bet.potential_win) }}₽
            </span>
          </div>
          <div class="bet-date">
            {{ formatDate(bet.created_at) }}
          </div>
        </div>
      </div>
    </div>

    <button @click="loadBets" class="refresh-btn" :disabled="loading">
      {{ loading ? 'Загрузка...' : 'Обновить' }}
    </button>
  </div>
</template>

<script>
import { betsAPI } from '../services/api'
import { formatBalance, formatDate, getStatusText } from '../utils/formatters'

export default {
  name: 'BetsList',
  data() {
    return {
      bets: [],
      loading: false
    }
  },
  mounted() {
    this.loadBets()
  },
  methods: {
    async loadBets() {
      this.loading = true
      try {
        const response = await betsAPI.getAll()
        this.bets = response.data.bets || []
      } catch (error) {
        console.error('Error loading bets:', error)
        this.bets = []
      } finally {
        this.loading = false
      }
    },
    formatBalance,
    formatDate,
    getStatusText,
    getStatusClass(status) {
      return `status-${status}`
    }
  }
}
</script>

<style scoped>
.bets-list {
  background: white;
  padding: 20px;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.loading, .empty {
  text-align: center;
  padding: 40px;
  color: #6b7280;
}

.bet-item {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 15px;
  background: #f9fafb;
}

.bet-item.status-won {
  border-color: #10b981;
  background: #f0fdf4;
}

.bet-item.status-lost {
  border-color: #ef4444;
  background: #fef2f2;
}

.bet-item.status-pending {
  border-color: #f59e0b;
  background: #fffbeb;
}

.bet-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.bet-id {
  font-weight: bold;
  color: #6b7280;
}

.bet-status {
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: bold;
  text-transform: uppercase;
}

.status-won .bet-status {
  background: #10b981;
  color: white;
}

.status-lost .bet-status {
  background: #ef4444;
  color: white;
}

.status-pending .bet-status {
  background: #f59e0b;
  color: white;
}

.status-cancelled .bet-status {
  background: #6b7280;
  color: white;
}

.event-title {
  font-weight: bold;
  color: #1f2937;
  margin-bottom: 8px;
}

.bet-outcome {
  margin-bottom: 8px;
  color: #374151;
}

.bet-amounts {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 10px;
  margin-bottom: 10px;
}

.bet-amounts span {
  font-size: 14px;
  color: #6b7280;
}

.bet-date {
  font-size: 12px;
  color: #9ca3af;
  border-top: 1px solid #e5e7eb;
  padding-top: 8px;
}

.refresh-btn {
  width: 100%;
  padding: 10px;
  background: #2563eb;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 15px;
}

.refresh-btn:hover:not(:disabled) {
  background: #1d4ed8;
}

.refresh-btn:disabled {
  background: #9ca3af;
  cursor: not-allowed;
}
</style>