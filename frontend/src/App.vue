<template>
  <div class="app">
    <header class="header">
      <h1>🎰 Сервис ставок</h1>
      <p class="subtitle">Безопасные ставки с защитой от мошенничества</p>
    </header>

    <main class="main">
      <div v-if="!isAuthenticated" class="auth-screen">
        <LoginForm @login-success="handleLoginSuccess" />
      </div>

      <div v-else class="dashboard">
        <UserInfo 
          :user="currentUser" 
          @logout="handleLogout"
        />
        
        <div class="content">
          <div class="left-column">
            <BetForm 
              :user-balance="currentUser.balance"
              @bet-created="handleBetCreated"
            />
          </div>
          
          <div class="right-column">
            <BetsList />
          </div>
        </div>
      </div>
    </main>

    <footer class="footer">
      <p>Тестовое задание: Laravel + Vue2 + Docker</p>
    </footer>
  </div>
</template>

<script>
import LoginForm from './components/LoginForm.vue'
import UserInfo from './components/UserInfo.vue'
import BetForm from './components/BetForm.vue'
import BetsList from './components/BetsList.vue'

export default {
  name: 'App',
  components: {
    LoginForm,
    UserInfo,
    BetForm,
    BetsList
  },
  data() {
    return {
      isAuthenticated: false,
      currentUser: null
    }
  },
  mounted() {
    this.checkAuthStatus()
  },
  methods: {
    checkAuthStatus() {
      const token = localStorage.getItem('auth_token')
      const user = localStorage.getItem('user')
      
      if (token && user) {
        try {
          this.currentUser = JSON.parse(user)
          this.isAuthenticated = true
          return true
        } catch (error) {
          this.clearAuth()
          return false
        }
      }
      return false
    },
    handleLoginSuccess(user) {
      this.currentUser = user
      this.isAuthenticated = true
    },
    handleLogout() {
      this.clearAuth()
    },
    handleBetCreated(newBalance) {
      this.currentUser.balance = newBalance
      localStorage.setItem('user', JSON.stringify(this.currentUser))
    },
    clearAuth() {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
      this.isAuthenticated = false
      this.currentUser = null
    }
  }
}
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  background: #f8fafc;
  color: #1f2937;
  line-height: 1.6;
}

.app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 2rem 1rem;
  text-align: center;
}

.header h1 {
  font-size: 2.5rem;
  margin-bottom: 0.5rem;
  font-weight: 700;
}

.subtitle {
  font-size: 1.1rem;
  opacity: 0.9;
  font-weight: 300;
}

.main {
  flex: 1;
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1rem;
  width: 100%;
}

.auth-screen {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 400px;
}

.dashboard {
  width: 100%;
}

.content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
}

@media (max-width: 768px) {
  .content {
    grid-template-columns: 1fr;
  }
  
  .header h1 {
    font-size: 2rem;
  }
  
  .main {
    padding: 1rem;
  }
}

.footer {
  background: #374151;
  color: #9ca3af;
  text-align: center;
  padding: 1rem;
  font-size: 0.9rem;
}
</style>