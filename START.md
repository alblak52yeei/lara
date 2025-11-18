# Инструкция по запуску проекта

## Быстрый запуск

### 1. Установка зависимостей (если еще не установлены)

```bash
# Установка PHP зависимостей (если нужно)
composer install

# Установка NPM зависимостей
npm install
```

### 2. Настройка окружения

```bash
# Создание .env файла (если его нет)
cp .env.example .env

# Генерация ключа приложения
php artisan key:generate

# Запуск миграций
php artisan migrate
```

### 3. Запуск проекта

#### Вариант 1: Полный запуск (сервер + очереди + логи + Vite)
```bash
composer run dev
```

#### Вариант 2: Только PHP сервер
```bash
php artisan serve
```
Приложение будет доступно по адресу: http://localhost:8000

#### Вариант 3: PHP сервер + Vite (для разработки с фронтендом)
```bash
# Терминал 1: PHP сервер
php artisan serve

# Терминал 2: Vite
npm run dev
```

## Доступные маршруты

### Web маршруты:
- `GET /` - Главная страница
- `GET /form` - Форма для отправки данных
- `POST /form` - Отправка формы
- `GET /data` - Просмотр данных

### API маршруты (v2.0):
- `GET /api/v2/categories` - Список категорий
- `POST /api/v2/categories` - Создание категории
- `GET /api/v2/categories/{id}` - Просмотр категории
- `PUT/PATCH /api/v2/categories/{id}` - Обновление категории
- `DELETE /api/v2/categories/{id}` - Удаление категории

- `GET /api/v2/contacts` - Список контактов
- `POST /api/v2/contacts` - Создание контакта
- `GET /api/v2/contacts/{id}` - Просмотр контакта
- `PUT/PATCH /api/v2/contacts/{id}` - Обновление контакта
- `DELETE /api/v2/contacts/{id}` - Удаление контакта

- `GET /api/v2/comments` - Список комментариев
- `POST /api/v2/comments` - Создание комментария
- `GET /api/v2/comments/{id}` - Просмотр комментария
- `PUT/PATCH /api/v2/comments/{id}` - Обновление комментария
- `DELETE /api/v2/comments/{id}` - Удаление комментария

## Примеры использования API

### Создание категории:
```bash
curl -X POST http://localhost:8000/api/v2/categories \
  -H "Content-Type: application/json" \
  -d '{"name": "Техническая поддержка", "description": "Категория для технических вопросов"}'
```

### Создание контакта:
```bash
curl -X POST http://localhost:8000/api/v2/contacts \
  -H "Content-Type: application/json" \
  -d '{"name": "Иван Иванов", "email": "ivan@example.com", "phone": "+79991234567", "message": "Тестовое сообщение", "status": "new"}'
```

### Получение контактов с фильтрацией:
```bash
# Новые контакты
curl http://localhost:8000/api/v2/contacts?new_only=1

# Поиск
curl http://localhost:8000/api/v2/contacts?search=Иван

# По статусу
curl http://localhost:8000/api/v2/contacts?status=new

# С категориями и комментариями
curl http://localhost:8000/api/v2/contacts?with_category=1&with_comments=1
```

## Очистка кэша (при необходимости)

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
```

