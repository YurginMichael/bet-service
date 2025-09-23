# Мини‑сервис ставок (Laravel + MySQL, Vue 2, Docker)

Проект под тестовое задание: бэкенд на Laravel + MySQL, фронтенд на Vue 2. Запуск через Docker (docker-compose up --build).

## Запуск

1. Скопируйте переменные окружения при необходимости: `.env.example` → `.env` (в корне).
2. Соберите и запустите контейнеры:
```
docker-compose up --build
```
3. Инициализация выполняется автоматически в контейнере `app`:
   - Установка зависимостей, генерация ключа приложения.
   - Миграции и сиды (пользователи, события, балансы).
4. Доступы:
   - API: `http://localhost:8081`
   - Frontend: `http://localhost:8080`

## Функциональность

- Аутентификация пользователя (Sanctum) и защищённые эндпоинты.
- Эндпоинты:
  - POST `/api/bets` — создать ставку (event_id, outcome, amount).
  - GET `/api/bets` — список ставок текущего пользователя.
  - GET `/api/events` — список событий (из сидов).
- Списание баланса и фиксация транзакции при создании ставки.
- Защита от double-spend (транзакции/блокировки БД, уникальные ключи идемпотентности).
- Идемпотентность по заголовку `Idempotency-Key`.
- Rate limiting ставок (например, 10 запросов/минуту).
- Проверка подписи `X-Signature` (HMAC).
- Логирование подозрительных действий в `fraud_logs`.

## Архитектура

- Backend: Laravel (PHP-FPM) + Nginx + MySQL.
- Frontend: Vue 2 (dev‑server с прокси на API).
- Миграции и сиды — стандартные каталоги Laravel. Данные поднимаются при старте.
- Безопасность: Sanctum, HMAC подпись, идемпотентность, rate limiting, журналирование.

## Команды

- Запуск: `docker-compose up --build`
- Остановка: `docker-compose down`
- Миграции/сиды:
```
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --force
```
- Тесты:
```
docker-compose exec app php artisan test
```

## Переменные окружения

- `APP_ENV`, `APP_DEBUG`
- `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `SIGNING_SECRET` — секрет для `X-Signature` (HMAC)

## Структура

- `backend/` — Laravel приложение
- `frontend/` — Vue 2 приложение
- `docker/` — Dockerfile’ы и конфигурация Nginx
- `docker-compose.yml` — сервисы
