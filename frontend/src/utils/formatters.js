export const formatBalance = (balance) => {
  return parseFloat(balance).toFixed(2)
}

export const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleString('ru-RU', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  })
}

export const getStatusText = (status) => {
  const statusMap = {
    'pending': 'В ожидании',
    'won': 'Выиграна',
    'lost': 'Проиграна',
    'cancelled': 'Отменена'
  }
  return statusMap[status] || status
}