<template>
  <div class="auth-form">
    <div v-if="isLogin" class="form-section">
      <h2>Вход в систему</h2>
      <form @submit.prevent="login">
        <div class="form-group">
          <label for="email">Email:</label>
          <input 
            id="email"
            type="email" 
            v-model="form.email" 
            required 
            placeholder="user1@test.com"
          />
        </div>
        <div class="form-group">
          <label for="password">Пароль:</label>
          <input 
            id="password"
            type="password" 
            v-model="form.password" 
            required 
            placeholder="password123"
          />
        </div>
        <button type="submit" :disabled="loading">
          {{ loading ? 'Вход...' : 'Войти' }}
        </button>
      </form>
      
      <div class="switch-form">
        <p>Нет аккаунта?</p>
        <button @click="switchToRegister" class="switch-btn">
          Зарегистрироваться
        </button>
      </div>
    </div>

    <div v-else class="form-section">
      <h2>Регистрация</h2>
      <form @submit.prevent="register">
        <div class="form-group">
          <label for="reg-name">Имя:</label>
          <input 
            id="reg-name"
            type="text" 
            v-model="registerForm.name" 
            required 
            placeholder="Ваше имя"
          />
        </div>
        <div class="form-group">
          <label for="reg-email">Email:</label>
          <input 
            id="reg-email"
            type="email" 
            v-model="registerForm.email" 
            required 
            placeholder="your@email.com"
          />
        </div>
        <div class="form-group">
          <label for="reg-password">Пароль:</label>
          <input 
            id="reg-password"
            type="password" 
            v-model="registerForm.password" 
            required 
            placeholder="Минимум 8 символов"
            minlength="8"
          />
        </div>
        <div class="form-group">
          <label for="reg-password-confirm">Подтверждение пароля:</label>
          <input 
            id="reg-password-confirm"
            type="password" 
            v-model="registerForm.password_confirmation" 
            required 
            placeholder="Повторите пароль"
          />
        </div>
        <button type="submit" :disabled="loading || !passwordsMatch">
          {{ loading ? 'Регистрация...' : 'Зарегистрироваться' }}
        </button>
      </form>
      
      <div class="switch-form">
        <p>Уже есть аккаунт?</p>
        <button @click="switchToLogin" class="switch-btn">
          Войти
        </button>
      </div>
    </div>

    <p v-if="error" class="error">{{ error }}</p>
    <p v-if="success" class="success">{{ success }}</p>
    
    <div v-if="isLogin" class="demo-accounts">
      <h3>Демо аккаунты:</h3>
      <p><strong>user1@test.com</strong> / password123 (Баланс: 5000₽)</p>
      <p><strong>user2@test.com</strong> / password123 (Баланс: 1000₽)</p>
      <p><strong>poor@test.com</strong> / password123 (Баланс: 10₽)</p>
    </div>
  </div>
</template>

<script>
import { authAPI } from '../services/api'

export default {
  name: 'LoginForm',
  data() {
    return {
      isLogin: true,
      form: {
        email: '',
        password: ''
      },
      registerForm: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      loading: false,
      error: '',
      success: ''
    }
  },
  computed: {
    passwordsMatch() {
      return this.registerForm.password === this.registerForm.password_confirmation
    }
  },
  methods: {
    switchToLogin() {
      this.isLogin = true
      this.clearMessages()
    },
    switchToRegister() {
      this.isLogin = false
      this.clearMessages()
    },
    clearMessages() {
      this.error = ''
      this.success = ''
    },
    async login() {
      this.loading = true
      this.clearMessages()
      
      try {
        const response = await authAPI.login(this.form)
        
        if (response.data.token) {
          localStorage.setItem('auth_token', response.data.token)
          localStorage.setItem('user', JSON.stringify(response.data.user))
          this.$emit('login-success', response.data.user)
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'Ошибка входа'
      } finally {
        this.loading = false
      }
    },
    async register() {
      if (!this.passwordsMatch) {
        this.error = 'Пароли не совпадают'
        return
      }

      this.loading = true
      this.clearMessages()
      
      try {
        const response = await authAPI.register(this.registerForm)
        
        if (response.data.token) {
          localStorage.setItem('auth_token', response.data.token)
          localStorage.setItem('user', JSON.stringify(response.data.user))
          this.$emit('login-success', response.data.user)
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'Ошибка регистрации'
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style scoped>
.auth-form {
  max-width: 400px;
  margin: 0 auto;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  background: #fff;
}

.form-section {
  padding: 20px;
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

input {
  width: 100%;
  padding: 10px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  box-sizing: border-box;
  font-size: 14px;
}

input:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

button {
  width: 100%;
  padding: 12px;
  background: #2563eb;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 16px;
  font-weight: 500;
  transition: background-color 0.2s;
}

button:hover:not(:disabled) {
  background: #1d4ed8;
}

button:disabled {
  background: #9ca3af;
  cursor: not-allowed;
}

.switch-form {
  margin-top: 20px;
  padding-top: 20px;
  border-top: 1px solid #e5e7eb;
  text-align: center;
}

.switch-form p {
  margin: 0 0 10px 0;
  color: #6b7280;
  font-size: 14px;
}

.switch-btn {
  width: auto !important;
  padding: 8px 20px !important;
  background: #2563eb !important;
  color: white !important;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: background-color 0.2s;
}

.switch-btn:hover {
  background: #1d4ed8 !important;
}

.error {
  color: #dc2626;
  margin: 15px 0;
  padding: 10px;
  background: #fef2f2;
  border: 1px solid #fecaca;
  border-radius: 6px;
  font-size: 14px;
}

.success {
  color: #059669;
  margin: 15px 0;
  padding: 10px;
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  border-radius: 6px;
  font-size: 14px;
}

.demo-accounts {
  margin: 20px;
  padding: 15px;
  background: #f9fafb;
  border-radius: 6px;
}

.demo-accounts h3 {
  margin: 0 0 10px 0;
  font-size: 14px;
  color: #6b7280;
}

.demo-accounts p {
  margin: 5px 0;
  font-size: 12px;
  color: #6b7280;
}
</style>