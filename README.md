# Мини-сервис ставок

Laravel + MySQL + Vue 2 + Docker. Полнофункциональный сервис ставок с защитой от мошенничества.

## Быстрый старт

```bash
git clone <repository-url>
cd bet-service-test

# Запускаем контейнеры 
docker-compose up --build
```

**Доступы:**
- Frontend: http://localhost:8080
- API: http://localhost:8081


## Демо аккаунты

| Email | Пароль | Баланс |
|-------|--------|---------|
| user1@test.com | password123 | 5000₽ |
| user2@test.com | password123 | 1000₽ |
| poor@test.com | password123 | 10₽ |

## API Эндпоинты

- `POST /api/register` — регистрация
- `POST /api/login` — вход
- `GET /api/events` — список событий
- `POST /api/bets` — создание ставки
- `GET /api/bets` — история ставок
- `POST /api/bets/external` — внешний API с HMAC

## Безопасность

- **Защита от double-spend** — блокировки в БД
- **Идемпотентность** — заголовок `Idempotency-Key`
- **Rate Limiting** — 10 запросов/минуту
- **HMAC подписи** — `X-Signature` для внешних API
- **Fraud Logging** — логирование подозрительных действий

## Тестирование

```bash
# Все тесты
docker-compose exec app ./vendor/bin/phpunit tests/Feature/

# Конкретные тесты
docker-compose exec app ./vendor/bin/phpunit tests/Feature/BetCreationTest.php
docker-compose exec app ./vendor/bin/phpunit tests/Feature/AuthenticationTest.php
docker-compose exec app ./vendor/bin/phpunit tests/Feature/SecurityTest.php
```

**Результат:** 24 теста, 58 assertions, все проходят

## Команды

```bash
# Управление
docker-compose up --build          # С пересборкой и видимым процессом
docker-compose up -d               # В фоне
docker-compose down
docker-compose restart

# Laravel
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --force
docker-compose exec app ./vendor/bin/phpunit tests/Feature/

# Логи
docker-compose logs app --follow
```

