# test-project

Небольшое новостное приложение на PHP: главная страница с категориями, листинг постов по категории (с сортировкой и пагинацией) и страница отдельного поста с похожими статьями. Построено на собственном микрофреймворке (`framework/`) поверх PSR-интерфейсов, league/route и Smarty для шаблонов.

## Стек

- PHP 8.5, PSR-7/15/17 (`nyholm/psr7`), `league/route`, `php-di/php-di`
- Smarty 5 для вывода (`views/*.tpl`)
- MySQL 8.4 (PDO)
- Monolog для логирования
- Sass + Bootstrap 5 для стилей (`assets/scss` → `public/css`)
- PHPUnit для функциональных тестов, PHP CS Fixer для стиля кода
- Docker / docker compose для локального окружения

## Структура проекта

```
app/            бизнес-логика: Entity, Repository, Http\Controllers, Http\Request
framework/      микрофреймворк: Application, Routing, ViewFactory, Storage,
                LoggerFactory, ExceptionHandling, ContainerBuilder, Http\Emitter
configs/        конфигурация приложения (routes, dependencies, db, params,
                exceptions, boot_test — оверрайды для тестов)
database/       SQL-схема (tables.sql) и сиды (seed.sql)
docker/         конфиги для docker-окружения (mysql/conf.d/charset.cnf)
views/          Smarty-шаблоны
assets/scss     исходники стилей
public/         точка входа и собранные ассеты (css)
tests/          функциональные тесты (PHPUnit), bootstrap.php, FunctionalTestCase
```

## Быстрый старт (Docker)

1. Скопировать `.env.example` в `.env` (делается автоматически при `make up`) и при необходимости поправить значения.
2. Поднять контейнеры:

   ```bash
   make up
   ```

3. Создать схему БД:

   ```bash
   make init-db
   ```

4. Засеять тестовыми данными (опционально):

   ```bash
   make seed
   ```

5. Приложение будет доступно на `APP_URL` из `.env` (по умолчанию `http://127.0.0.1:80/`, порт пробрасывается через `APP_SERVER`).

### Прочие команды Makefile

```bash
make recreate-db   # дропнуть и пересоздать основную базу данных
make init-test-db  # создать схему в тестовой базе (test-db), вызывается автоматически перед test
make cs            # исправить стиль кода (php-cs-fixer fix)
make cs-check      # проверить стиль кода без изменений (dry-run)
make test          # прогнать PHPUnit внутри контейнера app против test-db
```

## Тесты

Функциональные тесты (`tests/Functional`) поднимают `Application` поверх реального PDO-подключения к отдельной базе `test-db` и дёргают контроллеры через `FunctionalTestCase::get()`. Конфиг `configs/boot_test.php` переопределяет `boot.envFile` на `.env.test`, а `make test`:

1. создаёт схему в `test-db` (`init-test-db`);
2. прокидывает переменные из `.env.test` в контейнер `app` через `docker compose exec -e ...`;
3. запускает `composer test` (phpunit, конфиг `phpunit.xml`).

После каждого теста `FunctionalTestCase` чистит все таблицы (`TRUNCATE`) и восстанавливает глобальный exception handler.

## Переменные окружения

См. `.env.example`:

- `COMPOSE_PROJECT_NAME`, `APP_SERVER`, `DB_PORT`, `TEST_DB_PORT` — настройки Docker (порты `db` и `test-db` разведены)
- `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` — подключение к основной MySQL (`db`); `DB_HOST` не задан в `.env.example` и по умолчанию равен `db` (см. `configs/db.php`)
- `TEST_DB_DATABASE`, `TEST_DB_USERNAME`, `TEST_DB_PASSWORD` — подключение к тестовой MySQL (`test-db`) для Docker-сервиса
- `LOG_LEVEL` — уровень логирования (Monolog)
- `LOG_DRIVER` — драйвер логирования Monolog (`stdout` по умолчанию, в `.env.example` — `rotating_file`)
- `APP_URL` — публичный URL приложения

## Маршруты

| Метод | Путь                                   | Контроллер                              |
|-------|-----------------------------------------|------------------------------------------|
| GET   | `/`                                      | `HomeController::index`                  |
| GET   | `/categories/{categoryId}/posts`         | `PostsController::getAllByCategoryId`     |
| GET   | `/categories/{categoryId}/posts/{id}`    | `PostsController::getOne`                 |
