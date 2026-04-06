<?php

declare(strict_types=1);

namespace Tests\DatetimeInflector;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use Haikiri\DatetimeInflector\RussianInflector;
use PHPUnit\Framework\TestCase;

class RussianInflectorTest extends TestCase
{

	public static function testNow(): void
	{
		# Умеем работать с объектом времени.
		$now = new DateTime("2000-01-01 00:00:00");
		$result = RussianInflector::prepare(start: $now, end: $now);
		self::assertSame("сейчас", $result);
	}

	/** @throws Exception */
	public static function testTimeZone(): void
	{
		$start = new DateTimeImmutable("2024-01-01 12:00:00", new DateTimeZone("UTC"));
		$end = new DateTimeImmutable("2024-01-01 15:00:00", new DateTimeZone("Europe/Moscow"));

		$result = RussianInflector::prepare(start: $start, end: $end);
		self::assertSame("сейчас", $result);
	}

	public static function testAgoMinutes(): void
	{
		$result = RussianInflector::prepare(start: "2000-01-01 00:01:00", end: "2000-01-01 00:00:00");
		self::assertSame("1 минуту назад", $result);

		$result = RussianInflector::prepare("2000-01-01 00:02:00", "2000-01-01 00:00:00");
		self::assertSame("2 минуты назад", $result);

		$result = RussianInflector::prepare("2000-01-01 00:05:00", "2000-01-01 00:00:00");
		self::assertSame("5 минут назад", $result);

		$result = RussianInflector::prepare("2000-01-01 00:20:00", "2000-01-01 00:00:00");
		self::assertSame("20 минут назад", $result);

		$result = RussianInflector::prepare("2000-01-01 00:21:01", "2000-01-01 00:00:01");
		self::assertSame("21 минуту назад", $result);
	}

	public static function testAgoHours(): void
	{
		$result = RussianInflector::prepare(start: "2000-01-01 01:00:00", end: "2000-01-01 00:00:00");
		self::assertSame("1 час назад", $result);

		$result = RussianInflector::prepare(start: "2000-01-01 02:00:00", end: "2000-01-01 00:00:00");
		self::assertSame("2 часа назад", $result);

		$result = RussianInflector::prepare(start: "2000-01-01 05:00:00", end: "2000-01-01 00:00:00");
		self::assertSame("5 часов назад", $result);

		$result = RussianInflector::prepare(start: "2000-01-01 20:00:00", end: "2000-01-01 00:00:00");
		self::assertSame("20 часов назад", $result);

		$result = RussianInflector::prepare(start: "2000-01-01 21:00:02", end: "2000-01-01 00:00:02");
		self::assertSame("21 час назад", $result);
	}

	public static function testAgoDays(): void
	{
		$result = RussianInflector::prepare(start: "2000-01-16 00:00:00", end: "2000-01-15 00:00:00");
		self::assertSame("вчера в 00:00", $result);

		$result = RussianInflector::prepare(start: "2000-01-16 15:35:00", end: "2000-01-15 15:35:00");
		self::assertSame("вчера в 15:35", $result);

		$result = RussianInflector::prepare(start: "2000-01-16 23:59:59", end: "2000-01-15 23:59:59");
		self::assertSame("вчера в 23:59", $result);

		$result = RussianInflector::prepare(start: "2000-01-16 00:00:00", end: "2000-01-14 00:00:00");
		self::assertSame("позавчера в 00:00", $result);

		$result = RussianInflector::prepare(start: "2000-01-16 00:00:00", end: "2000-01-13 00:00:00");
		self::assertSame("3 дня назад", $result);

		$result = RussianInflector::prepare(start: "2000-01-16 00:00:00", end: "2000-01-11 00:00:00");
		self::assertSame("5 дней назад", $result);

		$result = RussianInflector::prepare(start: "2000-01-16 00:00:00", end: "1999-12-27 00:00:00");
		self::assertSame("20 дней назад", $result);

		$result = RussianInflector::prepare(start: "2000-01-16 00:00:03", end: "1999-12-26 00:00:03");
		self::assertSame("21 день назад", $result);
	}

	public static function testAgoYears(): void
	{
		$result = RussianInflector::prepare(start: "2001-01-01 00:00:00", end: "2000-01-01 00:00:00");
		self::assertSame("1 год назад", $result);

		$result = RussianInflector::prepare(start: "2002-01-01 00:00:00", end: "2000-01-01 00:00:00");
		self::assertSame("2 года назад", $result);

		$result = RussianInflector::prepare(start: "2030-01-01 00:00:00", end: "2025-01-01 00:00:00");
		self::assertSame("5 лет назад", $result);

		$result = RussianInflector::prepare(start: "2030-01-01 00:00:00", end: "2010-01-01 00:00:00");
		self::assertSame("20 лет назад", $result);

		$result = RussianInflector::prepare(start: "2035-01-01 00:00:00", end: "2005-01-01 00:00:00");
		self::assertSame("30 лет назад", $result);
	}

	public static function testAgoYearsAndMonth(): void
	{
		$result = RussianInflector::prepare(start: "2001-12-01 00:00:00", end: "2000-01-01 00:00:00");
		self::assertSame("1 год 11 месяцев назад", $result);

		$result = RussianInflector::prepare(start: "2002-02-01 00:00:00", end: "2000-01-01 00:00:00");
		self::assertSame("2 года 1 месяц назад", $result);

		$result = RussianInflector::prepare(start: "2030-03-01 00:00:00", end: "2025-01-01 00:00:00");
		self::assertSame("5 лет 2 месяца назад", $result);

		$result = RussianInflector::prepare(start: "2030-06-01 00:00:00", end: "2010-01-01 00:00:00");
		self::assertSame("20 лет 5 месяцев назад", $result);
	}

	public static function testAfterMinutes(): void
	{
		$result = RussianInflector::prepare("2000-01-01 00:00:00", "2000-01-01 00:01:00");
		self::assertSame("через 1 минуту", $result);

		$result = RussianInflector::prepare("2000-01-01 00:00:00", "2000-01-01 00:02:00");
		self::assertSame("через 2 минуты", $result);

		$result = RussianInflector::prepare("2000-01-01 00:00:00", "2000-01-01 00:05:00");
		self::assertSame("через 5 минут", $result);

		$result = RussianInflector::prepare("2000-01-01 00:00:00", "2000-01-01 00:20:00");
		self::assertSame("через 20 минут", $result);

		$result = RussianInflector::prepare("2000-01-01 00:00:00", "2000-01-01 00:21:00");
		self::assertSame("через 21 минуту", $result);
	}

	public static function testAfterHours(): void
	{
		$result = RussianInflector::prepare("1999-12-31 23:00:00", "2000-01-01 00:00:00");
		self::assertSame("через 1 час", $result);

		$result = RussianInflector::prepare("1999-12-31 23:00:00", "2000-01-01 01:00:00");
		self::assertSame("через 2 часа", $result);

		$result = RussianInflector::prepare("1999-12-31 23:00:00", "2000-01-01 04:00:00");
		self::assertSame("через 5 часов", $result);

		$result = RussianInflector::prepare("1999-12-31 23:00:00", "2000-01-01 20:00:00");
		self::assertSame("через 21 час", $result);

		$result = RussianInflector::prepare("1999-12-31 23:00:00", "2000-01-01 22:00:00");
		self::assertSame("через 23 часа", $result);

		$result = RussianInflector::prepare("1999-12-31 23:00:00", "2000-01-01 23:00:00");
		self::assertSame("завтра в 23:00", $result);

		$result = RussianInflector::prepare("1999-12-31 23:00:00", "2000-01-02 05:00:00");
		self::assertSame("завтра в 05:00", $result);

		$result = RussianInflector::prepare("1999-12-31 23:00:00", "2000-01-03 05:00:00");
		self::assertSame("послезавтра в 05:00", $result);

		$result = RussianInflector::prepare("1999-12-31 23:00:00", "2000-01-04 05:00:00");
		self::assertSame("через 3 дня 05:00", $result);
	}

	public static function testAfterYears(): void
	{
		$result = RussianInflector::prepare("2000-01-01 00:00:00", "2001-01-01 00:00:00");
		self::assertSame("через 1 год", $result);

		$result = RussianInflector::prepare("2000-01-01 00:00:00", "2002-01-01 00:00:00");
		self::assertSame("через 2 года", $result);

		$result = RussianInflector::prepare("2000-01-01 00:00:00", "2005-01-01 00:00:00");
		self::assertSame("через 5 лет", $result);

		$result = RussianInflector::prepare("2000-01-01 00:00:00", "2021-01-01 00:00:00");
		self::assertSame("через 21 год", $result);

		$result = RussianInflector::prepare("2000-01-01 00:00:00", "2025-01-01 00:00:00");
		self::assertSame("через 25 лет", $result);

		$result = RussianInflector::prepare("2000-01-01 00:00:00", "2033-01-01 00:00:00");
		self::assertSame("через 33 года", $result);
	}

}
