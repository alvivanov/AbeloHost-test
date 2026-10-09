# test-project

Небольшое новостное приложение на PHP: главная страница с категориями, листинг постов по категории (с сортировкой и пагинацией) и страница отдельного поста с похожими статьями. Построено на собственном микрофреймворке (`framework/`) поверх PSR-интерфейсов, league/route и Smarty для шаблонов.

## Стек

- PHP 8.5, PSR-7/15/17 (`nyholm/psr7`), `league/route`, `php-di/php-di`
- Smarty 5 для вывода (`views/*.tpl`)
- MySQL 8.4 (PDO)
- Monolog для логирования
- Sass + Bootstrap 5 для стилей (`assets/scss` → `public/css`)
- PHPUnit для тестов, PHP CS Fixer для стиля кода
- Docker / docker compose для локального окружения

## Структура проекта

```
app/            бизнес-логика: Entity, Repository, Http\Controllers, Http\Request
framework/      микрофреймворк: Application, Routing, ViewFactory, Storage,
                LoggerFactory, ExceptionHandling, ContainerBuilder, Http\Emitter
configs/        конфигурация приложения (routes, dependencies, db, params, exceptions)
database/       SQL-схема (tables.sql) и сиды (seed.sql)
docker/         конфиги для docker-окружения (mysql/conf.d/charset.cnf)
views/          Smarty-шаблоны
assets/scss     исходники стилей
public/         точка входа и собранные ассеты (css)
tests/          Unit и Functional тесты (PHPUnit)
```

## Быстрый старт (Docker)

1. Скопировать `.env.example` в `.env` (делается автоматически при `make up`) и при необходимости поправить значения.
2. Поднять контейнеры:

   ```bash
   make up
   ```

   Это соберёт образ (`Dockerfile`, PHP 8.5-cli-alpine + Xdebug) и запустит сервисы `php` и `mysql` (см. `compose.yaml`). Контейнер `php` выполняет `composer run dev`, который ставит зависимости, поднимает встроенный сервер PHP (`0.0.0.0:80`) и запускает вотчер Sass.

3. Создать схему БД:

   ```bash
   make init-db
   ```

4. Засеять тестовыми данными (опционально):

   ```bash
   make seed
   ```

5. Приложение будет доступно на `APP_URL` из `.env` (по умолчанию `http://127.0.0.1:9000/`, порт пробрасывается через `APP_SERVER`).

### Прочие команды Makefile

```bash
make recreate-db   # дропнуть и пересоздать базу данных
make cs            # исправить стиль кода (php-cs-fixer fix)
make cs-check      # проверить стиль кода без изменений (dry-run)
make test          # запустить PHPUnit внутри контейнера
```

## Переменные окружения

См. `.env.example`:

- `COMPOSE_PROJECT_NAME`, `APP_SERVER`, `DB_PORT` — настройки Docker
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` — подключение к MySQL
- `LOG_LEVEL` — уровень логирования (Monolog)
- `APP_URL` — публичный URL приложения

## Маршруты

| Метод | Путь                                   | Контроллер                              |
|-------|-----------------------------------------|------------------------------------------|
| GET   | `/`                                      | `HomeController::index`                  |
| GET   | `/categories/{categoryId}/posts`         | `PostsController::getAllByCategoryId`     |
| GET   | `/categories/{categoryId}/posts/{id}`    | `PostsController::getOne`                 |
