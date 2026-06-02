# GrantAI — Інтелектуальна система пошуку грантів

AI-агент для автоматизованого пошуку та оцінки грантових програм на основі Gemini API від Google. Система аналізує профіль користувача, підбирає релевантні гранти за 10 метриками та надсилає персональні рекомендації на email.

## Можливості

- Пошук грантів за запитом у вільній формі
- Оцінка грантів за 10 метриками з показником збігу від 0 до 100%
- Виділення TOP-3 найбільш релевантних грантів
- Персональний профіль користувача для точнішого підбору
- Автоматичне email-сповіщення з TOP-3 грантами після кожного пошуку
- Автоматичний моніторинг нових грантів щоп'ятниці о 9:00

## Технологічний стек

- PHP 8.2
- Laravel 12
- MySQL
- Gemini 2.5 Flash API
- Gmail SMTP
- HTML / CSS / Blade

## Вимоги

- PHP >= 8.2
- Composer
- MySQL
- Laragon або XAMPP
- Gemini API ключ (безкоштовно на ai.google.dev)
- Gmail акаунт з увімкненою двофакторною автентифікацією

## Встановлення та запуск

### 1. Клонування репозиторію

```bash
git clone https://github.com/gerasimovamargo/grant-ai-system.git
cd grant-ai-system
```

### 2. Встановлення залежностей

```bash
composer install
```

### 3. Налаштування середовища

Скопіюйте файл прикладу та відкрийте для редагування:

```bash
cp .env.example .env
```

Заповніть такі параметри у файлі `.env`:

```env
APP_NAME=GrantAI
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=grant_system
DB_USERNAME=root
DB_PASSWORD=

GEMINI_API_KEY=ваш_ключ_від_gemini_api

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ваш_gmail@gmail.com
MAIL_PASSWORD=ваш_app_password
MAIL_FROM_ADDRESS=ваш_gmail@gmail.com
MAIL_FROM_NAME="GrantAI"
```

### 4. Генерація ключа застосунку

```bash
php artisan key:generate
```

### 5. Створення бази даних

Створіть базу даних `grant_system` у MySQL, потім виконайте міграції:

```bash
php artisan migrate
```

### 6. Запуск сервера

```bash
php artisan serve
```

Застосунок буде доступний за адресою: **http://localhost:8000**

### 7. Запуск планувальника

Відкрийте другий термінал і виконайте:

```bash
php artisan schedule:work
```

## Налаштування Gemini API

1. Зайдіть на [ai.google.dev](https://ai.google.dev)
2. Створіть проєкт та отримайте безкоштовний API ключ
3. Вставте ключ у `.env` як `GEMINI_API_KEY`

Безкоштовний план дозволяє до 20 запитів на день для моделі gemini-2.5-flash.

## Налаштування Gmail

1. Зайдіть на [myaccount.google.com](https://myaccount.google.com)
2. Безпека → увімкніть двофакторну автентифікацію
3. Паролі додатків → створіть новий пароль для "GrantAI"
4. Скопіюйте згенерований пароль (16 символів без пробілів) у `MAIL_PASSWORD`

## Використання

1. Зареєструйтесь або увійдіть в систему
2. Заповніть профіль: **Мій профіль** → вкажіть тип організації, сферу діяльності, бюджет та інші параметри
3. На головній сторінці введіть запит у вільній формі, наприклад: *"Шукаю грант на навчання в Європі в сфері IT"*
4. Натисніть **Знайти гранти** — система підбере 10 грантів і виділить TOP-3
5. TOP-3 гранти автоматично надійдуть на пошту

## Ручний запуск моніторингу

```bash
php artisan grants:monitor
```


