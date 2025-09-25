<template>
  <div class="user-info">
    <div class="user-details">
      <h3>Добро пожаловать, {{ user.name }}!</h3>
      <div class="balance">
        <span class="balance-label">Баланс:</span>
        <span class="balance-amount">{{ formatBalance(user.balance) }}₽</span>
      </div>
    </div>
    <button @click="logout" class="logout-btn">Выйти</button>
  </div>
</template>

<script>
import { formatBalance } from '../utils/formatters'

export default {
  name: 'UserInfo',
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  methods: {
    formatBalance,
    logout() {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
      this.$emit('logout')
    }
  }
}
</script>

<style scoped>
.user-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  background: #f8fafc;
  border-radius: 8px;
  margin-bottom: 20px;
}

.user-details h3 {
  margin: 0 0 10px 0;
  color: #1f2937;
}

.balance {
  display: flex;
  align-items: center;
  gap: 10px;
}

.balance-label {
  color: #6b7280;
  font-weight: 500;
}

.balance-amount {
  font-size: 20px;
  font-weight: bold;
  color: #059669;
}

.logout-btn {
  padding: 8px 16px;
  background: #dc2626;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
}

.logout-btn:hover {
  background: #b91c1c;
}
</style>