### Описание
**Task Manager** — система управления задачами: создание задач, назначение исполнителей, изменение статусов. Проект завершает обучение на Хекслете и включает работу с ORM, CRUD, авторизацией, формами, фильтрацией, тестами и интеграцией Rollbar.

### Hexlet tests and linter status:
[![Actions Status](https://github.com/barsheel/php-project-57/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/barsheel/php-project-57/actions)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=barsheel_php-project-57&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=barsheel_php-project-57)

### Задеплоенное приложение
[https://php-project-57-ibuh.onrender.com](https://php-project-57-ibuh.onrender.com)

### Системные требования
<li>PHP ^8.2</li>
<li>Node ^20.19.2</li>
<li>npm</li>
<li>make</li>

### Установка
<ol>
<li>Загрузка</li>

```shell
git clone https://github.com/barsheel/php-project-57
cd php-project-57
```

<li>Установка</li>
    
    
```bash
make install
```

Для подключения вашей БД используйте следующие переменные окружения

```text
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=password
```

<li>Запуск сервера</li>

```bash
make start
```

<li>Доступ</li>

```bash
http://localhost:8000
```
</ol>


