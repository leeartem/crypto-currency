# TEST Crypto Currency
Тестовое задание получения курса BTC в разных валютах и конвертация.

Изначально хотел добавить еще репозиторий кэша курсов через редис, но что-то у меня снова температура поднимается...

## Запуск
> docker compose up -d

## Куда смотреть
Курсы
> http://localhost/api/v1?method=rates

Курс USD
> http://localhost/api/v1?method=rates&currency=USD

Обмен USD > BTC
> http://localhost/api/v1?method=convert&currency_from=USD&currency_to=BTC&value=43000

Обмен BTC > USD
> http://localhost/api/v1?method=convert&currency_from=BTC&currency_to=USD&value=1

## Bearer токен
Записан в конфиге auth, но для удобства вынесу и сюда:
> pYi3oX8qWzRvK2aL7fHbTcJ6eMxN1uV0sSgDhFtGnZlA5B4I9_UoYwQrXkE3is1-

## Тесты
Добавил немного тестов, запуск:
> docker-compose run --rm artisan test

# SQL запрос
Первая часть тестового задания лежит в корне этого проекта в файле
> QUERY.SQL