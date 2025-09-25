FROM node:18-alpine

WORKDIR /usr/src/app

# Копируем package.json и package-lock.json
COPY package*.json ./

# Копируем остальные файлы
COPY . .

EXPOSE 8080

# Устанавливаем зависимости во время сборки
RUN npm install --no-cache --silent

# Создаем скрипт запуска
RUN echo '#!/bin/sh\nset -e\necho "Starting Vue.js development server..."\nnpm run serve' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
