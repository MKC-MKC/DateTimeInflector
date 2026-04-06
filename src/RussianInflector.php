<?php

declare(strict_types=1);

namespace Haikiri\DatetimeInflector;

use DateTime;
use DateTimeInterface;
use Exception;
use Haikiri\DeclensionHelper\Declension;

final class RussianInflector
{

	/**
	 * @param DateTimeInterface|string|null $start
	 * @param DateTimeInterface|string|null $end
	 * @return string
	 */
	public static function prepare(DateTimeInterface|string|null $start, DateTimeInterface|string|null $end): string
	{
		try {
			# Подготовка объектов времени.
			$startDate = self::prepareDate($start);
			$endDate = self::prepareDate($end);

			# Расчёт времени.
			$isPast = $endDate < $startDate;
			$interval = $startDate->diff($endDate);

			# Разбиваем разницу объекта времени.
			$day = (int)$interval->days;
			$year = $interval->y;
			$month = $interval->m;
			$hour = $interval->h;
			$minute = $interval->i;
			$time = $endDate->format("H:i");

			# Подготавливаем шаблоны.
			Declension::set("year", ["год", "года", "лет"]);
			Declension::set("month", ["месяц", "месяца", "месяцев"]);
			Declension::set("day", ["день", "дня", "дней"]);
			Declension::set("hour", ["час", "часа", "часов"]);
			Declension::set("minute", ["минуту", "минуты", "минут"]);

			# Формирование шаблона.
			$template_year = Declension::format($year, "year");
			$template_month = Declension::format($month, "month");
			$template_day = Declension::format($day, "day");
			$template_hour = Declension::format($hour, "hour");
			$template_minute = Declension::format($minute, "minute");

			# Годы и месяцы.
			if ($year > 0 && $month > 0) {
				return $isPast ? "$template_year $template_month назад" : "через $template_year $template_month";
			}

			# Годы.
			if ($year > 0) {
				return $isPast ? sprintf("%s назад", $template_year) : sprintf("через %s", $template_year);
			}

			# Только месяцы.
			if ($month > 0) {
				return $isPast ? sprintf("%s назад", $template_month) : sprintf("через %s", $template_month);
			}

			# Обработка дней.
			if ($day > 0) {

				# Один день.
				if ($day === 1) {
					return $isPast ? sprintf("вчера в %s", $time) : sprintf("завтра в %s", $time);
				}

				# Два дня.
				if ($day === 2) {
					return $isPast ? sprintf("позавчера в %s", $time) : sprintf("послезавтра в %s", $time);
				}

				# 3 и более.
				return $isPast ? sprintf("%s назад", $template_day) : sprintf("через %s %s", $template_day, $time);
			}

			# Часы.
			if ($hour > 0) {
				return $isPast ? sprintf("%s назад", $template_hour) : sprintf("через %s", $template_hour);
			}

			# Минуты.
			if ($minute > 0) {
				return $isPast ? sprintf("%s назад", $template_minute) : sprintf("через %s", $template_minute);
			}

			return "сейчас";
		} catch (Exception) {
			return "неизвестно";
		}
	}

	/**
	 * Метод подготавливает объект времени.
	 * @param DateTimeInterface|string|null $value
	 * @return DateTime
	 * @throws Exception
	 */
	private static function prepareDate(DateTimeInterface|string|null $value): DateTime
	{
		if ($value instanceof DateTimeInterface) return DateTime::createFromInterface($value);
		return $value === null ? new DateTime() : new DateTime($value);
	}

}
