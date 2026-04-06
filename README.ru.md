# RuDateTimeInflector

Этот SDK превращает разницу между двумя датами в нормальную русскую фразу:

- `1 минуту назад`
- `вчера в 15:35`
- `через 2 года`
- `послезавтра в 05:00`

## Установка

Требуется Php >= 8.0

```bash
composer require haikiri/datetime-inflector
```

## Как использовать

Основной вызов один:

```php
use Haikiri\DatetimeInflector\RussianInflector;

$text = RussianInflector::prepare($start, $end);
```

Можно передавать:

- `null`, если нужна текущая дата
- строки дат, например `2000-01-01 00:00:00`
- любой наследуемый объект `DateTimeInterface`

## Примеры

Все примеры покрыты в тестах, включая работу с часовыми поясами.

```php
<?php

use DateTime;
use Haikiri\DatetimeInflector\RussianInflector;

# 1 минуту назад
echo RussianInflector::prepare("2000-01-01 00:01:00", "2000-01-01 00:00:00");

# вчера в 15:35
echo RussianInflector::prepare("2000-01-16 15:35:00", "2000-01-15 15:35:00");

# через 5 минут
echo RussianInflector::prepare("2000-01-01 00:00:00", "2000-01-01 00:05:00");

# послезавтра в 05:00
echo RussianInflector::prepare("1999-12-31 23:00:00", "2000-01-03 05:00:00");

# Умеем работать с объектами времени:
# через 2 года 1 месяц
echo RussianInflector::prepare(
    new DateTime("2000-01-01 00:00:00"),
    new DateTime("2002-02-01 00:00:00"),
);
```
