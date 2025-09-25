import axios from 'axios'

const apiClient = axios.create({
  baseURL: process.env.NODE_ENV === 'development' 
    ? 'http://localhost:8081' 
    : 'http://nginx'
})

apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
      window.location.reload()
    }
    return Promise.reject(error)
  }
)

export const authAPI = {
  login: (credentials) => apiClient.post('/api/login', credentials),
  register: (userData) => apiClient.post('/api/register', userData),
  logout: () => apiClient.post('/api/logout')
}

export const eventsAPI = {
  getAll: () => apiClient.get('/api/events')
}

export const betsAPI = {
  getAll: () => apiClient.get('/api/bets'),
  create: (betData) => {
    const idempotencyKey = `bet_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`
    return apiClient.post('/api/bets', betData, {
      headers: {
        'Idempotency-Key': idempotencyKey
      }
    })
  }
}

export const userAPI = {
  getProfile: () => apiClient.get('/api/user')
}

export default apiClient